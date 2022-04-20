<?php
declare(strict_types=1);

namespace customies\block;

use customies\item\CustomiesItemFactory;
use customies\task\AsyncClosureTask;
use InvalidArgumentException;
use OutOfRangeException;
use pocketmine\block\Block;
use pocketmine\block\BlockBreakInfo;
use pocketmine\block\BlockFactory;
use pocketmine\block\BlockIdentifier;
use pocketmine\data\bedrock\LegacyBlockIdToStringIdMap;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\convert\GlobalItemTypeDictionary;
use pocketmine\network\mcpe\convert\R12ToCurrentBlockMapEntry;
use pocketmine\network\mcpe\convert\RuntimeBlockMapping;
use pocketmine\network\mcpe\protocol\serializer\NetworkNbtSerializer;
use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;
use pocketmine\network\mcpe\protocol\serializer\PacketSerializerContext;
use pocketmine\network\mcpe\protocol\types\BlockPaletteEntry;
use pocketmine\network\mcpe\protocol\types\CacheableNbt;
use pocketmine\network\mcpe\protocol\types\ItemTypeEntry;
use pocketmine\Server;
use pocketmine\utils\Utils;
use ReflectionClass;
use ReflectionException;
use RuntimeException;
use SplFixedArray;
use function array_fill;
use function array_keys;
use function array_map;
use function array_merge;
use function array_values;
use function count;
use function explode;
use function file_get_contents;
use function get_class;
use function ord;
use function strlen;
use function strtolower;
use function unserialize;
use function usort;
use const pocketmine\BEDROCK_DATA_PATH;

class CustomiesBlockFactory {

	private const NEW_BLOCK_FACTORY_SIZE = 32768;

	/**
	 * @var Block[]
	 * @phpstan-var array<string, Block>
	 */
	private static array $customBlocks = [];
	/**
	 * @var CompoundTag[]
	 * @phpstan-var array<int, CompoundTag>
	 */
	private static array $customBlockStates = [];
	/**
	 * @var Model[]
	 * @phpstan-var array<int, Model>
	 */
	private static array $customBlockModels = [];
	/**
	 * @var int[]
	 * @phpstan-var array<string, int>
	 */
	private static array $identifierToIdMap = [];
	/**
	 * @var CompoundTag[]
	 * @phpstan-var array<string, CompoundTag>
	 */
	private static array $identifierToStatesMap = [];
	/**
	 * @var int[]
	 * @phpstan-var array<string, int>
	 */
	private static array $identifierToRuntimeIdMap = [];
	/** @var BlockPaletteEntry[] */
	private static array $blockPaletteEntries = [];

	/**
	 * Adds a worker initialize hook to the async pool to sync the BlockFactory for every thread that is created.
	 */
	public static function addWorkerInitHook(): void {
		$blocks = serialize(self::$customBlocks);
		$models = serialize(self::$customBlockModels);
		$server = Server::getInstance();
		$server->getAsyncPool()->addWorkerStartHook(static function (int $worker) use ($server, $blocks, $models): void {
			$server->getAsyncPool()->submitTaskToWorker(new AsyncClosureTask(static function () use ($blocks, $models) {
				$blocks = unserialize($blocks);
				$models = unserialize($models);

				CustomiesBlockFactory::init();

				/**
				 * @var  $identifier string
				 * @var  $block      Block
				 */
				foreach($blocks as $identifier => $block){
					CustomiesBlockFactory::registerBlock(get_class($block), $identifier, $block->getName(), $block->getBreakInfo(), $models[$block->getId()] ?? null);
				}

				CustomiesBlockFactory::updateRuntimeMappings();
			}), $worker);
		});
	}

	/**
	 * Initializes the block factory and increases the default limits set by pocketmine.
	 *
	 * @throws ReflectionException
	 */
	public static function init(): void {
		self::increaseBlockFactoryLimits();
	}

	/**
	 * Updates all the RuntimeBlockMappings to sync pocketmine with the custom blocks.
	 *
	 * @throws ReflectionException
	 */
	public static function updateRuntimeMappings(): void {
		self::registerCustomKnownStates(self::$customBlockStates);
		self::registerCustomRuntimeMappings();
	}

	/**
	 * Get a custom block from its identifier. An exception will be thrown if the block is not registered.
	 *
	 * @param string $identifier
	 *
	 * @return Block
	 */
	public static function get(string $identifier): Block {
		$id = self::$identifierToIdMap[$identifier] ?? -1;
		if($id < 0) {
			throw new InvalidArgumentException("Custom block " . $identifier . " is not registered");
		}

		return BlockFactory::getInstance()->get($id, 0);
	}

	/**
	 * Returns all the block palette entries that need to be sent to the client.
	 *
	 * @return BlockPaletteEntry[]
	 */
	public static function getBlockPaletteEntries(): array {
		return self::$blockPaletteEntries;
	}

	/**
	 * Returns the identifier => legacyId map for all custom blocks.
	 *
	 * @return int[]
	 */
	public static function getIdentifierToIdMap(): array {
		return self::$identifierToIdMap;
	}

	/**
	 * Returns the next available custom block id, an exception will be thrown if the block factory is full.
	 *
	 * @return int
	 */
	private static function getNextAvailableId(): int {
		$id = 1000 + count(self::$identifierToIdMap);
		if($id > (self::NEW_BLOCK_FACTORY_SIZE / 16)) {
			throw new OutOfRangeException("All custom block ids are used up");
		}

		return $id;
	}

	/**
	 * Register a block to the BlockFactory and all the required mappings.
	 *
	 * @param string         $className
	 * @param string         $identifier
	 * @param string         $name
	 * @param BlockBreakInfo $breakInfo
	 * @param Model|null     $model
	 */
	public static function registerBlock(string $className, string $identifier, string $name, BlockBreakInfo $breakInfo, ?Model $model = null): void {
		if($className !== Block::class) {
			Utils::testValidInstance($className, Block::class);
		}

		/** @var Block $block */
		$block = new $className(new BlockIdentifier(self::getNextAvailableId(), 0), $name, $breakInfo);

		if(BlockFactory::getInstance()->isRegistered($block->getId())) {
			throw new InvalidArgumentException("Block with ID " . $block->getId() . " is already registered");
		}
		BlockFactory::getInstance()->register($block);
		CustomiesItemFactory::registerCustomItemMapping(255 - $block->getId());
		CustomiesItemFactory::addItemTypeEntry(new ItemTypeEntry($identifier, 255 - $block->getId(), false));

		$blockState = CompoundTag::create()
			->setString("name", $identifier)
			->setTag("states", CompoundTag::create());
		$runtimeId = self::getRuntimeId($blockState);

		/*if($model !== null) {
			$materialsTag = CompoundTag::create();
			foreach($model->getMaterials() as $material){
				$materialsTag->setTag($material->getTarget(), $material->toNBT());
			}
		}*/ // TODO: Is this needed?

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
				->setFloat("value", $block->getFrictionFactor() === 0.6 ? 0.1 : $block->getFrictionFactor()))
			->setTag("minecraft:flammable", CompoundTag::create()
				->setFloat("flame_odds", $block->getFlameEncouragement())
				->setFloat("burn_odds", $block->getFlammability()));

		if($model !== null) {
			foreach($model->toNBT() as $tagName => $tag){
				$components->setTag($tagName, $tag);
			}

			self::$customBlockModels[$block->getId()] = $model;
		}
		$propertiesTag->setTag("components", $components);

		//TODO: Support editing the loot table of the block and the map color

		self::$blockPaletteEntries[] = new BlockPaletteEntry($identifier, new CacheableNbt($propertiesTag));

		self::$customBlocks[$identifier] = $block;
		self::$customBlockStates[$runtimeId] = $blockState;
		self::$identifierToIdMap[$identifier] = $block->getId();
		self::$identifierToStatesMap[$identifier] = $blockState;
		//self::$identifierToRuntimeIdMap[explode(":", $identifier)[1]] = $runtimeId; // TODO: Is this needed?
	}

	/**
	 * Modifies the BlockFactory and increases the fixed array sizes within pocketmine. This function allows for up to
	 * 1048 new custom blocks to be registered.
	 *
	 * @throws ReflectionException
	 */
	public static function increaseBlockFactoryLimits(): void {
		$instance = BlockFactory::getInstance();
		$blockFactory = new ReflectionClass($instance);

		$fullListProperty = $blockFactory->getProperty("fullList");
		$fullListProperty->setAccessible(true);

		/** @var SplFixedArray $fullList */
		$fullList = $fullListProperty->getValue($instance);
		$fullList->setSize(self::NEW_BLOCK_FACTORY_SIZE);

		$fullListProperty->setValue($instance, $fullList);

		$mappedStateIdsProp = $blockFactory->getProperty("mappedStateIds");
		$mappedStateIdsProp->setAccessible(true);

		/** @var SplFixedArray $fullList */
		$mappedStateIds = $mappedStateIdsProp->getValue($instance);
		$mappedStateIds->setSize(self::NEW_BLOCK_FACTORY_SIZE);

		$mappedStateIdsProp->setValue($instance, $mappedStateIds);

		$instance->light = SplFixedArray::fromArray(array_fill(0, self::NEW_BLOCK_FACTORY_SIZE, 0));
		$instance->lightFilter = SplFixedArray::fromArray(array_fill(0, self::NEW_BLOCK_FACTORY_SIZE, 1));
		$instance->blocksDirectSkyLight = SplFixedArray::fromArray(array_fill(0, self::NEW_BLOCK_FACTORY_SIZE, false));
		$instance->blastResistance = SplFixedArray::fromArray(array_fill(0, self::NEW_BLOCK_FACTORY_SIZE, 0.0));
	}

	/**
	 * Registers the known states of the custom blocks so pocketmine can send them to the client when requested.
	 *
	 * @param array $customKnownStates
	 *
	 * @throws ReflectionException
	 */
	public static function registerCustomKnownStates(array $customKnownStates): void {
		$instance = RuntimeBlockMapping::getInstance();
		$runtimeBlockMapping = new ReflectionClass($instance);

		$knownStatesProperty = $runtimeBlockMapping->getProperty("bedrockKnownStates");
		$knownStatesProperty->setAccessible(true);

		/** @var CompoundTag[] $bedrockKnownStates */
		$bedrockKnownStates = $knownStatesProperty->getValue($instance);

		$states = array_merge($bedrockKnownStates, $customKnownStates);

		$groupedStates = [];
		array_map(static function (CompoundTag $tag) use (&$groupedStates): void {
			$name = $tag->getString("name", "minecraft:unknown");
			if(!isset($groupedStates[$name])) {
				$groupedStates[$name] = [$tag];
			} else {
				$groupedStates[$name][] = $tag;
			}
		}, $states);

		$stateNames = array_keys($groupedStates);
		usort($stateNames, static fn(string $a, string $b) => strcmp(hash("fnv164", $a), hash("fnv164", $b)));
		/*usort($stateNames, static function (string $a, string $b): int {
			$a = strtolower($a);
			$b = strtolower($b);
			for($i = 0, $length = strlen($a); $i < $length; ++$i){
				if(($b[$i] ?? "") === "") {
					return 1;
				}
				$charA = ord($a[$i]) === 95 ? 0 : ord($a[$i]);
				$charB = ord($b[$i]) === 95 ? 0 : ord($b[$i]);
				if($charA !== $charB) {
					return $charA < $charB ? -1 : 1;
				}
			}
			return 0;
		});*/ // This code is for when it was like fucking dumb as fucking fuck. Keeping in case they change

		$sortedStates = [];

//		$t = "";
		foreach($stateNames as $stateName){
			$states = $groupedStates[$stateName];
			foreach($states as $state){
//				/** @var CompoundTag $state */
//				$h = $stateName . " ";
//				$st = [];
//				foreach($state->getCompoundTag("states")->getValue() as $n => $tag){
//					$st[] = "[$n: " . $tag->getValue() . "]";
//				}
//				$t .= $h . implode(" ", $st) . "\n";
				$sortedStates[] = $state;
			}
		}

//		file_put_contents(Server::getInstance()->getDataPath() . "block_states.txt", $t);

		$knownStatesProperty->setValue($instance, array_values($sortedStates));
	}

	/**
	 * Registers the custom block runtime mappings
	 * to tell PocketMine about the custom blocks.
	 *
	 * @throws ReflectionException
	 */
	public static function registerCustomRuntimeMappings(): void {
		$instance = RuntimeBlockMapping::getInstance();
		$bedrockKnownStates = $instance->getBedrockKnownStates();
		$runtimeBlockMapping = new ReflectionClass($instance);

		$legacyMapProperty = $runtimeBlockMapping->getProperty("legacyToRuntimeMap");
		$legacyMapProperty->setAccessible(true);
		$legacyMapProperty->setValue($instance, []);
		$runtimeMapProperty = $runtimeBlockMapping->getProperty("runtimeToLegacyMap");
		$runtimeMapProperty->setAccessible(true);
		$runtimeMapProperty->setValue($instance, []);

		$registerMappingMethod = $runtimeBlockMapping->getMethod("registerMapping");
		$registerMappingMethod->setAccessible(true);
		$registerMapping = $registerMappingMethod->getClosure($instance);
		if($registerMapping === null) {
			throw new RuntimeException("Unable to access mapping registration");
		}

		$legacyIdMap = LegacyBlockIdToStringIdMap::getInstance();
		/** @var R12ToCurrentBlockMapEntry[] $legacyStateMap */
		$legacyStateMap = [];

		$legacyStateMapReader = PacketSerializer::decoder(file_get_contents(BEDROCK_DATA_PATH . "r12_to_current_block_map.bin"), 0, new PacketSerializerContext(GlobalItemTypeDictionary::getInstance()->getDictionary()));
		$nbtReader = new NetworkNbtSerializer();
		while(!$legacyStateMapReader->feof()){
			$id = $legacyStateMapReader->getString();
			$meta = $legacyStateMapReader->getLShort();

			$offset = $legacyStateMapReader->getOffset();
			$state = $nbtReader->read($legacyStateMapReader->getBuffer(), $offset)->mustGetCompoundTag();
			$legacyStateMapReader->setOffset($offset);
			$legacyStateMap[] = new R12ToCurrentBlockMapEntry($id, $meta, $state);
		}

		foreach(self::$identifierToStatesMap as $identifier => $state){
			$legacyStateMap[] = new R12ToCurrentBlockMapEntry($identifier, 0, $state);
		}

		/**
		 * @var int[][] $idToStatesMap string id -> int[] list of candidate state indices
		 */
		$idToStatesMap = [];
		foreach($bedrockKnownStates as $k => $state){
			$idToStatesMap[$state->getString("name")][] = $k;
		}

		foreach($legacyStateMap as $pair){
			$id = $legacyIdMap->stringToLegacy($pair->getId()) ?? (self::$identifierToIdMap[$pair->getId()] ?? null);
			if($id === null) {
				throw new \RuntimeException("No legacy ID matches " . $pair->getId());
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
				$networkState = $bedrockKnownStates[$k];
				if($mappedState->equals($networkState)) {
					$registerMapping($k, $id, $data);
					continue 2;
				}
			}
		}
	}

	private static function getRuntimeId(CompoundTag $customState): int {
		$states = array_merge(RuntimeBlockMapping::getInstance()->getBedrockKnownStates(), self::$customBlockStates, [$customState]);

		$groupedStates = [];
		array_map(static function (CompoundTag $tag) use (&$groupedStates): void {
			$name = $tag->getString("name", "minecraft:unknown");
			if(!isset($groupedStates[$name])) {
				$groupedStates[$name] = [$tag];
			} else {
				$groupedStates[$name][] = $tag;
			}
		}, $states);

		$stateNames = array_keys($groupedStates);
		usort($stateNames, static fn(string $a, string $b) => strcmp(hash("fnv164", $a), hash("fnv164", $b)));
		/*usort($stateNames, static function (string $a, string $b): int {
			$a = strtolower($a);
			$b = strtolower($b);
			for($i = 0, $length = strlen($a); $i < $length; ++$i){
				if(($b[$i] ?? "") === "") {
					return 1;
				}
				$charA = ord($a[$i]) === 95 ? 0 : ord($a[$i]);
				$charB = ord($b[$i]) === 95 ? 0 : ord($b[$i]);
				if($charA !== $charB) {
					return $charA < $charB ? -1 : 1;
				}
			}
			return 0;
		});*/ // This code is for when it was like fucking dumb as fucking fuck. Keeping in case they change

		$sortedStates = [];
		$i = 0;
		foreach($stateNames as $stateName){
			$states = $groupedStates[$stateName];
			foreach($states as $state){
				$sortedStates[] = $state;
			}
			/*if(($oldId = self::$identifierToRuntimeIdMap[$stateName] ?? -1) >= 0) {
				self::$identifierToRuntimeIdMap[$stateName] = $i;
				self::$customBlockStates[$i] = self::$customBlockStates[$oldId];
				unset(self::$customBlockStates[$oldId]);
			}*/ // TODO: Update state runtime ids if they changed from registering new blocks
			++$i;
		}

		/**
		 * @var  $runtimeId int
		 * @var  $state     CompoundTag
		 */
		foreach($sortedStates as $runtimeId => $state){
			if($state->getString("name", "minecraft:unknown") === $customState->getString("name", "minecraft:unknown")) {
				return $runtimeId;
			}
		}
		return -1;
	}
}