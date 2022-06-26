<?php
declare(strict_types=1);

namespace customiesdevs\customies\block;

use customiesdevs\customies\item\CreativeInventoryInfo;
use customiesdevs\customies\item\CustomiesItemFactory;
use customiesdevs\customies\task\AsyncRegisterBlocksTask;
use customiesdevs\customies\world\LegacyBlockIdToStringIdMap;
use InvalidArgumentException;
use OutOfRangeException;
use pocketmine\block\Block;
use pocketmine\block\BlockBreakInfo;
use pocketmine\block\BlockFactory;
use pocketmine\block\BlockIdentifier;
use pocketmine\inventory\CreativeInventory;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\convert\GlobalItemTypeDictionary;
use pocketmine\network\mcpe\convert\R12ToCurrentBlockMapEntry;
use pocketmine\network\mcpe\convert\RuntimeBlockMapping;
use pocketmine\network\mcpe\protocol\serializer\NetworkNbtSerializer;
use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;
use pocketmine\network\mcpe\protocol\serializer\PacketSerializerContext;
use pocketmine\network\mcpe\protocol\types\BlockPaletteEntry;
use pocketmine\network\mcpe\protocol\types\CacheableNbt;
use pocketmine\Server;
use pocketmine\utils\SingletonTrait;
use pocketmine\utils\Utils;
use ReflectionClass;
use RuntimeException;
use SplFixedArray;
use function array_fill;
use function count;
use function file_get_contents;
use const pocketmine\BEDROCK_DATA_PATH;

final class CustomiesBlockFactory {
	use SingletonTrait;

	private const NEW_BLOCK_FACTORY_SIZE = 2048 << Block::INTERNAL_METADATA_BITS;

	/**
	 * @var Block[]
	 * @phpstan-var array<string, Block>
	 */
	private array $customBlocks = [];
	/** @var BlockPaletteEntry[] */
	private array $blockPaletteEntries = [];
	/** @var R12ToCurrentBlockMapEntry[] */
	private array $legacyStateMap = [];

	public function __construct() {
		$this->increaseBlockFactoryLimits();
	}

	/**
	 * Modifies the properties in the BlockFactory instance to increase the SplFixedArrays to double the limit of blocks
	 * that can be registered.
	 */
	public function increaseBlockFactoryLimits(): void {
		$instance = BlockFactory::getInstance();
		$blockFactory = new ReflectionClass($instance);
		foreach(["fullList", "mappedStateIds"] as $propertyName){
			$property = $blockFactory->getProperty($propertyName);
			$property->setAccessible(true);
			/** @var SplFixedArray $array */
			$array = $property->getValue($instance);
			$array->setSize(self::NEW_BLOCK_FACTORY_SIZE);
			$property->setValue($instance, $array);
		}
		$instance->light = SplFixedArray::fromArray(array_fill(0, self::NEW_BLOCK_FACTORY_SIZE, 0));
		$instance->lightFilter = SplFixedArray::fromArray(array_fill(0, self::NEW_BLOCK_FACTORY_SIZE, 1));
		$instance->blocksDirectSkyLight = SplFixedArray::fromArray(array_fill(0, self::NEW_BLOCK_FACTORY_SIZE, false));
		$instance->blastResistance = SplFixedArray::fromArray(array_fill(0, self::NEW_BLOCK_FACTORY_SIZE, 0.0));
	}

	/**
	 * Adds a worker initialize hook to the async pool to sync the BlockFactory for every thread worker that is created.
	 * It is especially important for the workers that deal with chunk encoding, as using the wrong runtime ID mappings
	 * can result in massive issues with almost every block showing as the wrong thing and causing lag to clients.
	 */
	public function addWorkerInitHook(): void {
		$blocks = serialize($this->customBlocks);
		$server = Server::getInstance();
		$server->getAsyncPool()->addWorkerStartHook(static function (int $worker) use ($server, $blocks): void {
			$server->getAsyncPool()->submitTaskToWorker(new AsyncRegisterBlocksTask($blocks), $worker);
		});
	}

	/**
	 * Get a custom block from its identifier. An exception will be thrown if the block is not registered.
	 */
	public function get(string $identifier): Block {
		$id = LegacyBlockIdToStringIdMap::getInstance()->stringToLegacy($identifier) ?? -1;
		if($id < 0) {
			throw new InvalidArgumentException("Custom block " . $identifier . " is not registered");
		}

		return BlockFactory::getInstance()->get($id, 0);
	}

	/**
	 * Returns all the block palette entries that need to be sent to the client.
	 * @return BlockPaletteEntry[]
	 */
	public function getBlockPaletteEntries(): array {
		return $this->blockPaletteEntries;
	}

	/**
	 * Register a block to the BlockFactory and all the required mappings.
	 * @phpstan-param class-string $className
	 */
	public function registerBlock(string $className, string $identifier, string $name, BlockBreakInfo $breakInfo, ?Model $model = null, ?CreativeInventoryInfo $creativeInfo = null): void {
		if($className !== Block::class) {
			Utils::testValidInstance($className, Block::class);
		}

		/** @var Block $block */
		$block = new $className(new BlockIdentifier($this->getNextAvailableId(), 0), $name, $breakInfo);

		if(BlockFactory::getInstance()->isRegistered($block->getId())) {
			throw new InvalidArgumentException("Block with ID " . $block->getId() . " is already registered");
		}
		BlockFactory::getInstance()->register($block);
		CustomiesItemFactory::getInstance()->registerBlockItem($identifier, $block->getId());

		$blockState = CompoundTag::create()
			->setString("name", $identifier)
			->setTag("states", CompoundTag::create());
		BlockPalette::getInstance()->insertState($blockState);

		$propertiesTag = CompoundTag::create();
		$components = CompoundTag::create()
			->setTag("minecraft:block_light_emission", CompoundTag::create()
				->setFloat("value", (float)$block->getLightLevel() / 15))
			->setTag("minecraft:block_light_filter", CompoundTag::create()
				->setInt("value", $block->getLightFilter()))
			->setTag("minecraft:destroy_time", CompoundTag::create()
				->setFloat("value", $block->getBreakInfo()->getHardness()))
			->setTag("minecraft:explosion_resistance", CompoundTag::create()
				->setFloat("value", $block->getBreakInfo()->getBlastResistance()))
			->setTag("minecraft:friction", CompoundTag::create()
				->setFloat("value", $block->getFrictionFactor()))
			->setTag("minecraft:flammable", CompoundTag::create()
				->setFloat("flame_odds", $block->getFlameEncouragement())
				->setFloat("burn_odds", $block->getFlammability()));

		if($model !== null) {
			foreach($model->toNBT() as $tagName => $tag){
				$components->setTag($tagName, $tag);
			}
		}

		$creativeInfo ??= CreativeInventoryInfo::DEFAULT();
		$components->setTag("minecraft:creative_category", CompoundTag::create()
			->setString("category", $creativeInfo->getCategory())
			->setString("group", $creativeInfo->getGroup()));
		$propertiesTag->setTag("components", $components);
		CreativeInventory::getInstance()->add($block->asItem());

		$this->blockPaletteEntries[] = new BlockPaletteEntry($identifier, new CacheableNbt($propertiesTag));

		$this->customBlocks[$identifier] = $block;
		LegacyBlockIdToStringIdMap::getInstance()->registerMapping($identifier, $block->getId());
	}

	/**
	 * Returns the next available custom block id, an exception will be thrown if the block factory is full.
	 */
	private function getNextAvailableId(): int {
		$id = 1000 + count($this->customBlocks);
		if($id > (self::NEW_BLOCK_FACTORY_SIZE / 16)) {
			throw new OutOfRangeException("All custom block ids are used up");
		}

		return $id;
	}

	/**
	 * Registers the custom block runtime mappings to tell PocketMine about the custom blocks.
	 */
	public function registerCustomRuntimeMappings(): void {
		$instance = RuntimeBlockMapping::getInstance();
		$runtimeBlockMapping = new ReflectionClass($instance);

		foreach(["legacyToRuntimeMap", "runtimeToLegacyMap"] as $propertyName){
			$property = $runtimeBlockMapping->getProperty($propertyName);
			$property->setAccessible(true);
			$property->setValue($instance, []);
		}

		$registerMappingMethod = $runtimeBlockMapping->getMethod("registerMapping");
		$registerMappingMethod->setAccessible(true);
		$registerMapping = $registerMappingMethod->getClosure($instance);
		if($registerMapping === null) {
			throw new RuntimeException("Unable to access mapping registration");
		}

		$legacyIdMap = LegacyBlockIdToStringIdMap::getInstance();
		/** @var R12ToCurrentBlockMapEntry[] $legacyStateMap */
		$legacyStateMap = [];

		$legacyStateMapReader = PacketSerializer::decoder((string)file_get_contents(BEDROCK_DATA_PATH . "r12_to_current_block_map.bin"), 0, new PacketSerializerContext(GlobalItemTypeDictionary::getInstance()->getDictionary()));
		$nbtReader = new NetworkNbtSerializer();
		while(!$legacyStateMapReader->feof()){
			$id = $legacyStateMapReader->getString();
			$meta = $legacyStateMapReader->getLShort();

			$offset = $legacyStateMapReader->getOffset();
			$state = $nbtReader->read($legacyStateMapReader->getBuffer(), $offset)->mustGetCompoundTag();
			$legacyStateMapReader->setOffset($offset);
			$legacyStateMap[] = new R12ToCurrentBlockMapEntry($id, $meta, $state);
		}

		foreach(BlockPalette::getInstance()->getCustomStates() as $state){
			$legacyStateMap[] = new R12ToCurrentBlockMapEntry($state->getString("name"), 0, $state);
		}

		/**
		 * @var int[][] $idToStatesMap string id -> int[] list of candidate state indices
		 */
		$idToStatesMap = [];
		$states = BlockPalette::getInstance()->getStates();
		foreach($states as $k => $state){
			$idToStatesMap[$state->getString("name")][] = $k;
		}

		foreach($legacyStateMap as $pair){
			$id = $legacyIdMap->stringToLegacy($pair->getId());
			if($id === null) {
				throw new RuntimeException("No legacy ID matches " . $pair->getId());
			}
			$data = $pair->getMeta();
			if($data > 15) {
				continue;
			}
			$mappedState = $pair->getBlockState();
			$mappedName = $mappedState->getString("name");
			if(!isset($idToStatesMap[$mappedName])) {
				continue;
			}
			foreach($idToStatesMap[$mappedName] as $k){
				$networkState = $states[$k];
				if($mappedState->equals($networkState)) {
					$registerMapping($k, $id, $data);
					continue 2;
				}
			}
		}
	}
}
