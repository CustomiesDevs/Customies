<?php
declare(strict_types=1);

namespace customiesdevs\customies\block;

use pocketmine\Server;
use pocketmine\inventory\CreativeInventory;
use pocketmine\block\{Block, RuntimeBlockStateRegistry};
use pocketmine\nbt\tag\{CompoundTag, ListTag};
use pocketmine\network\mcpe\convert\{GlobalItemTypeDictionary, RuntimeBlockMapping};
use pocketmine\network\mcpe\protocol\{
	serializer\NetworkNbtSerializer, serializer\PacketSerializer, serializer\PacketSerializerContext,
	types\BlockPaletteEntry, types\CacheableNbt
};
use pocketmine\utils\SingletonTrait;

use customiesdevs\customies\block\permutations\{Permutable, Permutation, Permutations};
use customiesdevs\customies\item\{CreativeInventoryInfo, CustomiesItemFactory};
use customiesdevs\customies\util\{Cache, NBT};
use customiesdevs\customies\task\AsyncRegisterBlocksTask;
use customiesdevs\customies\world\LegacyBlockIdToStringIdMap;

use Closure;
use ReflectionClass;
use ReflectionException;
use RuntimeException;
use SplFixedArray;
use InvalidArgumentException;
use OutOfRangeException;

use function array_fill;
use function array_map;
use function count;
use function file_get_contents;
use const pocketmine\BEDROCK_DATA_PATH;

final class CustomiesBlockFactory {

	use SingletonTrait;

	private const NEW_BLOCK_FACTORY_SIZE = 2048 << Block::INTERNAL_STATE_DATA_BITS;

	/**
	 * @var Closure[]
	 * @phpstan-var array<string, Closure(int): Block>
	 */
	private array $blockFuncs = [];
	/** @var BlockPaletteEntry[] */
	private array $blockPaletteEntries = [];
	/** @var R12ToCurrentBlockMapEntry[] */
	private array $legacyStateMap = [];

    /**
     * @throws ReflectionException
     */
    public function __construct() {
		$this->increaseBlockFactoryLimits();
	}

    /**
     * Modifies the properties in the BlockFactory instance to increase the SplFixedArrays to double the limit of blocks
     * that can be registered.
     * @throws ReflectionException
     */
	public function increaseBlockFactoryLimits(): void {

		$instance = RuntimeBlockStateRegistry::getInstance();
		$runtimeBlockStateRegistry = new ReflectionClass($instance);
        $property = $runtimeBlockStateRegistry->getProperty("fullList");
        $property->setAccessible(true);
        /** @var SplFixedArray $array */
        $array = $property->getValue($instance);
        $array->setSize(self::NEW_BLOCK_FACTORY_SIZE);
        $property->setValue($instance, $array);

		$instance->light = array_merge($instance->light, array_fill(count($instance->light), self::NEW_BLOCK_FACTORY_SIZE, 0));
		$instance->lightFilter = array_merge($instance->lightFilter, array_fill(count($instance->lightFilter), self::NEW_BLOCK_FACTORY_SIZE, 1));
		$instance->blocksDirectSkyLight = array_merge($instance->blocksDirectSkyLight, array_fill(count($instance->blocksDirectSkyLight), self::NEW_BLOCK_FACTORY_SIZE, false));
		$instance->blastResistance = array_merge($instance->blastResistance, array_fill(count($instance->blastResistance), self::NEW_BLOCK_FACTORY_SIZE, 0.0));

	}

    /**
     * Adds a worker initialize hook to the async pool to sync the BlockFactory for every thread worker that is created.
     * It is especially important for the workers that deal with chunk encoding, as using the wrong runtime ID mappings
     * can result in massive issues with almost every block showing as the wrong thing and causing lag to clients.
     *
     * @param string $cachePath
     * @return void
     */
	public function addWorkerInitHook(string $cachePath): void {
		$server = Server::getInstance();
		$blocks = $this->blockFuncs;
		$server->getAsyncPool()->addWorkerStartHook(static function (int $worker) use ($cachePath, $server, $blocks): void {
			$server->getAsyncPool()->submitTaskToWorker(new AsyncRegisterBlocksTask($cachePath, $blocks), $worker);
		});
	}

	/**
	 * Get a custom block from its identifier. An exception will be thrown if the block is not registered.
	 */
	public function get(string $identifier): Block {
		return RuntimeBlockStateRegistry::getInstance()->fromTypeId(
            LegacyBlockIdToStringIdMap::getInstance()->stringToLegacy($identifier) ??
			throw new InvalidArgumentException("Custom block " . $identifier . " is not registered")
        );
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
     * @phpstan-param (Closure(int): Block) $blockFunc
     * @throws ReflectionException
     */
	public function registerBlock(Closure $blockFunc, string $identifier, ?Model $model = null, ?CreativeInventoryInfo $creativeInfo = null): void {

		$id = $this->getNextAvailableId($identifier);
		$block = $blockFunc($id);

		if(!$block instanceof Block)
			throw new InvalidArgumentException("Class returned from closure is not a Block");

		if(RuntimeBlockStateRegistry::getInstance()->isRegistered($id))
			throw new InvalidArgumentException("Block with ID " . $id . " is already registered");

		RuntimeBlockStateRegistry::getInstance()->register($block);
		CustomiesItemFactory::getInstance()->registerBlockItem($identifier, $block);

		$propertiesTag = CompoundTag::create();
		$components = CompoundTag::create()
			->setTag("minecraft:light_emission", CompoundTag::create()
				->setByte("emission", $block->getLightLevel()))
			->setTag("minecraft:block_light_filter", CompoundTag::create()
				->setByte("lightLevel", $block->getLightFilter()))
			->setTag("minecraft:destructible_by_mining", CompoundTag::create()
				->setFloat("value", $block->getBreakInfo()->getHardness()))
			->setTag("minecraft:destructible_by_explosion", CompoundTag::create()
				->setFloat("value", $block->getBreakInfo()->getBlastResistance()))
			->setTag("minecraft:friction", CompoundTag::create()
				->setFloat("value", $block->getFrictionFactor()))
			->setTag("minecraft:flammable", CompoundTag::create()
				->setInt("catch_chance_modifier", $block->getFlameEncouragement())
				->setInt("destroy_chance_modifier", $block->getFlammability()));

		if($model !== null)
			foreach($model->toNBT() as $tagName => $tag)
				$components->setTag($tagName, $tag);

		if($block instanceof Permutable) {

			$blockPropertyNames = $blockPropertyValues = $blockProperties = [];

			foreach($block->getBlockProperties() as $blockProperty){

				$blockPropertyNames[] = $blockProperty->getName();
				$blockPropertyValues[] = $blockProperty->getValues();
				$blockProperties[] = $blockProperty->toNBT();

			}

			$permutations = array_map(static fn(Permutation $permutation) => $permutation->toNBT(), $block->getPermutations());

			/**
			 * The 'minecraft:on_player_placing' component is required for the client to predict block placement, making
			 * it a smoother experience for the end-user.
			 */
			$components->setTag("minecraft:on_player_placing", CompoundTag::create());
			$propertiesTag->setTag("permutations", new ListTag($permutations));
			$propertiesTag->setTag("properties", new ListTag($blockProperties));

			foreach(Permutations::getCartesianProduct($blockPropertyValues) as $meta => $permutations){
				/**
				 * We need to insert states for every possible permutation to allow for all blocks to be used and to
				 * keep in sync with the client's block palette.
				 */
				$states = CompoundTag::create();
				foreach($permutations as $i => $value)
					$states->setTag($blockPropertyNames[$i], NBT::getTagType($value));

				$blockState = CompoundTag::create()
					->setString("name", $identifier)
					->setTag("states", $states);
				BlockPalette::getInstance()->insertState($blockState, $meta);

			}
		} else {
			// If a block does not contain any permutations we can just insert the one state.
			$blockState = CompoundTag::create()
				->setString("name", $identifier)
				->setTag("states", CompoundTag::create());
			BlockPalette::getInstance()->insertState($blockState);

		}

		$creativeInfo ??= CreativeInventoryInfo::DEFAULT();
		$propertiesTag->setTag("components",
			$components->setTag("minecraft:creative_category", CompoundTag::create()
				->setString("category", $creativeInfo->getCategory()->value)
				->setString("group", $creativeInfo->getGroup()->value)))
			->setTag("menu_category", CompoundTag::create()
				->setString("category", $creativeInfo->getCategory()->value ?? "")
				->setString("group", $creativeInfo->getGroup()->value ?? ""))
			->setInt("molangVersion", 1);

		CreativeInventory::getInstance()->add($block->asItem());

		$this->blockPaletteEntries[] = new BlockPaletteEntry($identifier, new CacheableNbt($propertiesTag));
		$this->blockFuncs[$identifier] = $blockFunc;

		LegacyBlockIdToStringIdMap::getInstance()->registerMapping($identifier, $id);

	}

    /**
     * Registers the custom block runtime mappings to tell PocketMine about the custom blocks.
     * @throws ReflectionException
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
		if($registerMapping === null)
            throw new RuntimeException("Unable to access mapping registration");

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

		foreach(BlockPalette::getInstance()->getCustomStates() as $state)
            $legacyStateMap[] = $state;

		/**
		 * @var int[][] $idToStatesMap string id -> int[] list of candidate state indices
		 */
		$idToStatesMap = [];
		$states = BlockPalette::getInstance()->getStates();

		foreach($states as $k => $state)
            $idToStatesMap[$state->getString("name")][] = $k;
		foreach($legacyStateMap as $pair){

			$id = $legacyIdMap->stringToLegacy($pair->getId());

			if($id === null)
                throw new RuntimeException("No legacy ID matches " . $pair->getId());

			$data = $pair->getMeta();

			if($data > 15)
                continue;
			$mappedState = $pair->getBlockState();
			$mappedName = $mappedState->getString("name");

			if(!isset($idToStatesMap[$mappedName]))
                continue;
			foreach($idToStatesMap[$mappedName] as $k){

				$networkState = $states[$k];

				if($mappedState->equals($networkState)) {

					$registerMapping($k, $id, $data);

					continue 2;
				}
			}
		}
	}

    /**
     * Returns the next available custom block id, an exception will be thrown if the block factory is full.
     *
     * @param string $identifier
     * @return int
     */
	private function getNextAvailableId(string $identifier): int {
		return ($id = Cache::getInstance()->getNextAvailableBlockID($identifier)) > (self::NEW_BLOCK_FACTORY_SIZE / 16) ?
			throw new OutOfRangeException("All custom block ids are used up") :
			$id;
	}
}
