<?php
declare(strict_types=1);

namespace customiesdevs\customies\block;

use Closure;
use customiesdevs\customies\block\permutations\Permutable;
use customiesdevs\customies\block\permutations\Permutation;
use customiesdevs\customies\block\permutations\Permutations;
use customiesdevs\customies\item\CreativeInventoryInfo;
use customiesdevs\customies\item\CustomiesItemFactory;
use customiesdevs\customies\task\AsyncRegisterBlocksTask;
use customiesdevs\customies\util\NBT;
use InvalidArgumentException;
use pocketmine\block\Block;
use pocketmine\block\RuntimeBlockStateRegistry;
use pocketmine\data\bedrock\block\BlockStateData;
use pocketmine\data\bedrock\block\convert\BlockStateReader;
use pocketmine\data\bedrock\block\convert\BlockStateWriter;
use pocketmine\inventory\CreativeCategory;
use pocketmine\inventory\CreativeInventory;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\network\mcpe\protocol\types\BlockPaletteEntry;
use pocketmine\network\mcpe\protocol\types\CacheableNbt;
use pocketmine\Server;
use pocketmine\utils\AssumptionFailedError;
use pocketmine\utils\SingletonTrait;
use pocketmine\world\format\io\GlobalBlockStateHandlers;
use function array_map;
use function array_reverse;
use function hash;
use function strcmp;
use function usort;

final class CustomiesBlockFactory {
	use SingletonTrait;

	/**
	 * @var Closure[]
	 * @phpstan-var array<string, array{(Closure(int): Block), (Closure(BlockStateWriter): Block), (Closure(Block): BlockStateReader)}>
	 */
	private array $blockFuncs = [];
	/** @var BlockPaletteEntry[] */
	private array $blockPaletteEntries = [];
	/** @var array<string, Block> */
	private array $customBlocks = [];

	/**
	 * Adds a worker initialize hook to the async pool to sync the BlockFactory for every thread worker that is created.
	 * It is especially important for the workers that deal with chunk encoding, as using the wrong runtime ID mappings
	 * can result in massive issues with almost every block showing as the wrong thing and causing lag to clients.
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
		return clone (
			$this->customBlocks[$identifier] ??
			throw new InvalidArgumentException("Custom block $identifier is not registered")
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
	 * Register a block to the BlockFactory and all the required mappings. A custom stateReader and stateWriter can be
	 * provided to allow for custom block state serialization.
	 * @phpstan-param (Closure(): Block) $blockFunc
	 * @phpstan-param null|(Closure(BlockStateWriter): Block) $serializer
	 * @phpstan-param null|(Closure(Block): BlockStateReader) $deserializer
	 */
	public function registerBlock(Closure $blockFunc, string $identifier, ?CreativeInventoryInfo $creativeInfo = null, ?Closure $serializer = null, ?Closure $deserializer = null): void {
		$block = $blockFunc();
		if(!$block instanceof Block) {
			throw new InvalidArgumentException("Class returned from closure is not a Block");
		}

		RuntimeBlockStateRegistry::getInstance()->register($block);
		CustomiesItemFactory::getInstance()->registerBlockItem($identifier, $block);
		$this->customBlocks[$identifier] = $block;

		$propertiesTag = CompoundTag::create();
		$components = CompoundTag::create();
		if($block instanceof BlockComponents) {
			foreach ($block->getComponents() as $component) {
				$components->setTag($component->getName(), $component->getValue());
			}
		}

		if($block instanceof Permutable) {
			$blockPropertyNames = $blockPropertyValues = $blockProperties = [];
			foreach($block->getBlockProperties() as $blockProperty){
				$blockPropertyNames[] = $blockProperty->getName();
				$blockPropertyValues[] = $blockProperty->getValues();
				$blockProperties[] = $blockProperty->toNBT();
			}
			$permutations = array_map(static fn(Permutation $permutation) => $permutation->toNBT(), $block->getPermutations());

			// The 'minecraft:on_player_placing' component is required for the client to predict block placement, making
			// it a smoother experience for the end-user.
			$components->setTag("minecraft:on_player_placing", CompoundTag::create());
			$propertiesTag
				->setTag("permutations", new ListTag($permutations))
				->setTag("properties", new ListTag(array_reverse($blockProperties))); // fix client-side order

			foreach(Permutations::getCartesianProduct($blockPropertyValues) as $meta => $permutations){
				// We need to insert states for every possible permutation to allow for all blocks to be used and to
				// keep in sync with the client's block palette.
				$states = CompoundTag::create();
				foreach($permutations as $i => $value){
					$states->setTag($blockPropertyNames[$i], NBT::getTagType($value));
				}
				$blockState = CompoundTag::create()
					->setString(BlockStateData::TAG_NAME, $identifier)
					->setTag(BlockStateData::TAG_STATES, $states);
				BlockPalette::getInstance()->insertState($blockState, $meta);
			}

			$serializer ??= static function (Permutable $block) use ($identifier, $blockPropertyNames) : BlockStateWriter {
				$b = BlockStateWriter::create($identifier);
				$block->serializeState($b);
				return $b;
			};
			$deserializer ??= static function (BlockStateReader $in) use ($block, $identifier, $blockPropertyNames) : Permutable {
				$b = CustomiesBlockFactory::getInstance()->get($identifier);
				assert($b instanceof Permutable);
				$b->deserializeState($in);
				return $b;
			};
		} else {
			// If a block does not contain any permutations we can just insert the one state.
			$blockState = CompoundTag::create()
				->setString(BlockStateData::TAG_NAME, $identifier)
				->setTag(BlockStateData::TAG_STATES, CompoundTag::create());
			BlockPalette::getInstance()->insertState($blockState);
			$serializer ??= static fn() => new BlockStateWriter($identifier);
			$deserializer ??= static fn(BlockStateReader $in) => $block;
		}
		GlobalBlockStateHandlers::getSerializer()->map($block, $serializer);
		GlobalBlockStateHandlers::getDeserializer()->map($identifier, $deserializer);

		$addToCreative = $creativeInfo !== null;

		$creativeInfo ??= CreativeInventoryInfo::DEFAULT();
		$components->setTag("minecraft:creative_category", CompoundTag::create()
			->setString("category", $creativeInfo->getCategory())
			->setString("group", $creativeInfo->getGroup()));
		$propertiesTag
			->setTag("components",
				$components->setTag("minecraft:creative_category", CompoundTag::create()
					->setString("category", $creativeInfo->getCategory())
					->setString("group", $creativeInfo->getGroup())))
			->setTag("menu_category", CompoundTag::create()
				->setString("category", $creativeInfo->getCategory() ?? "")
				->setString("group", $creativeInfo->getGroup() ?? ""))
			->setInt("molangVersion", 1);

		if($addToCreative){
			CreativeInventory::getInstance()->add($block->asItem(), match($creativeInfo->getCategory()){
				CreativeInventoryInfo::CATEGORY_CONSTRUCTION => CreativeCategory::CONSTRUCTION,
				CreativeInventoryInfo::CATEGORY_ITEMS => CreativeCategory::ITEMS,
				CreativeInventoryInfo::CATEGORY_NATURE => CreativeCategory::NATURE,
				CreativeInventoryInfo::CATEGORY_EQUIPMENT => CreativeCategory::EQUIPMENT,
				default => throw new AssumptionFailedError("Unknown category")
			});
		}

		$this->blockPaletteEntries[] = new BlockPaletteEntry($identifier, new CacheableNbt($propertiesTag));
		$this->blockFuncs[$identifier] = [$blockFunc, $serializer, $deserializer];

		// 1.20.60 added a new "block_id" field which depends on the order of the block palette entries. Every time we
		// insert a new block, we need to re-sort the block palette entries to keep in sync with the client.
		usort($this->blockPaletteEntries, static function(BlockPaletteEntry $a, BlockPaletteEntry $b): int {
			return strcmp(hash("fnv164", $a->getName()), hash("fnv164", $b->getName()));
		});
		foreach($this->blockPaletteEntries as $i => $entry) {
			$root = $entry->getStates()->getRoot()
				->setTag("vanilla_block_data", CompoundTag::create()
					->setInt("block_id", 10000 + $i));
			$this->blockPaletteEntries[$i] = new BlockPaletteEntry($entry->getName(), new CacheableNbt($root));
		}
	}
}
