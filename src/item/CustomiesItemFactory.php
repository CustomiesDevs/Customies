<?php
declare(strict_types=1);

namespace customiesdevs\customies\item;

use Closure;
use customiesdevs\customies\util\NBT;
use InvalidArgumentException;
use pocketmine\block\Block;
use pocketmine\data\bedrock\item\BlockItemIdMap;
use pocketmine\data\bedrock\item\SavedItemData;
use pocketmine\inventory\CreativeCategory;
use pocketmine\inventory\CreativeGroup;
use pocketmine\inventory\CreativeInventory;
use pocketmine\item\Item;
use pocketmine\item\StringToItemParser;
use pocketmine\item\ItemTypeIds;
use pocketmine\lang\Translatable;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\convert\TypeConverter;
use pocketmine\network\mcpe\protocol\types\CacheableNbt;
use pocketmine\network\mcpe\protocol\types\ItemTypeEntry;
use pocketmine\utils\AssumptionFailedError;
use pocketmine\utils\SingletonTrait;
use pocketmine\world\format\io\GlobalItemDataHandlers;
use ReflectionClass;
use RuntimeException;

use function array_values;

final class CustomiesItemFactory {
	use SingletonTrait;

	/**
	 * @var ItemTypeEntry[]
	 */
	private array $itemTableEntries = [];
	private array $groups = [];

	/**
	 * Get a custom item from its identifier. An exception will be thrown if the item is not registered.
	 */
	public function get(string $identifier, int $amount = 1): Item {
		$item = StringToItemParser::getInstance()->parse($identifier);
		if($item === null) {
			throw new InvalidArgumentException("Custom item " . $identifier . " is not registered");
		}
		return $item->setCount($amount);
	}

	private function loadGroups() : void {
		if($this->groups !== []){
			return;
		}
		foreach(CreativeInventory::getInstance()->getAllEntries() as $entry){
			$group = $entry->getGroup();
			if($group !== null){
				$this->groups[$group->getName()->getText()] = $group;
			}
		}
	}

	/**
	 * Returns custom item entries
	 * @return ItemTypeEntry[]
	 */
	public function getItemTableEntries(): array {
		return array_values($this->itemTableEntries);
	}

	/**
	 * Registers the item to the item factory and assigns it an ID. It also updates the required mappings and stores the
	 * item components if present.
	 * @phpstan-param class-string $className
	 */
	public function registerItem(Closure $itemFunc, string $identifier, ?CreativeInventoryInfo $creativeInfo = null): void {
		$item = $itemFunc(ItemTypeIds::newId());
		if(!$item instanceof Item) {
			throw new InvalidArgumentException("Class returned from closure is not a Item");
		}
		$itemId = $item->getTypeId();

		GlobalItemDataHandlers::getDeserializer()->map($identifier, fn() => clone $item);
		GlobalItemDataHandlers::getSerializer()->map($item, fn() => new SavedItemData($identifier));

		StringToItemParser::getInstance()->register($identifier, fn() => clone $item);

		// This is where the components are added to the item
		$componentBased = $item instanceof ItemComponents;
		$nbt = $this->createItemNbt($item, $identifier, $itemId, $creativeInfo);

		if($creativeInfo !== null){
			$this->loadGroups();
			if($creativeInfo->getCategory() === CreativeInventoryInfo::CATEGORY_ALL || $creativeInfo->getCategory() === CreativeInventoryInfo::CATEGORY_COMMANDS){
				return;
			}

			$group = $this->groups[$creativeInfo->getGroup()] ?? ($creativeInfo->getGroup() !== "" && $creativeInfo->getGroup() !== CreativeInventoryInfo::NONE ? new CreativeGroup(
				new Translatable($creativeInfo->getGroup()),
				$item
			) : null);

			if($group !== null){
				$this->groups[$group->getName()->getText()] = $group;
			}

			$category = match ($creativeInfo->getCategory()) {
				CreativeInventoryInfo::CATEGORY_CONSTRUCTION => CreativeCategory::CONSTRUCTION,
				CreativeInventoryInfo::CATEGORY_ITEMS => CreativeCategory::ITEMS,
				CreativeInventoryInfo::CATEGORY_NATURE => CreativeCategory::NATURE,
				CreativeInventoryInfo::CATEGORY_EQUIPMENT => CreativeCategory::EQUIPMENT,
				default => throw new AssumptionFailedError("Unknown category")
			};

			CreativeInventory::getInstance()->add($item, $category, $group);
		}	

		$this->itemTableEntries[$identifier] = $entry = new ItemTypeEntry($identifier, $itemId, $componentBased, $componentBased ? 1 : 0, new CacheableNbt($nbt));
		$this->registerCustomItemMapping($identifier, $itemId, $entry);
	}

	/**
	 * Creates the NBT data for the item.
	 */
	private function createItemNbt(Item $item, string $identifier, int $itemId, ?CreativeInventoryInfo $creativeInfo): CompoundTag {
		$components = CompoundTag::create();
		$properties = CompoundTag::create();

		if ($item instanceof ItemComponents) {
			foreach ($item->getComponents() as $component) {
				$tag = NBT::getTagType($component->getValue());
				if ($tag === null) {
					throw new RuntimeException("Failed to get tag type for component " . $component->getName());
				}
				if ($component->isProperty()) {
					$properties->setTag($component->getName(), $tag);
					continue;
				}
				$components->setTag($component->getName(), $tag);
			}
			if ($creativeInfo !== null) {
				$properties->setTag("creative_category", NBT::getTagType($creativeInfo->getNumericCategory()));
				$properties->setTag("creative_group", NBT::getTagType($creativeInfo->getGroup()));
			}
			$components->setTag("item_properties", $properties);
			return CompoundTag::create()
				->setTag("components", $components)
				->setInt("id", $itemId)
				->setString("name", $identifier);
		}
		return CompoundTag::create();
	}

	/**
	 * Registers a custom item ID to the required mappings in the global ItemTypeDictionary instance.
	 */
	private function registerCustomItemMapping(string $identifier, int $itemId, ItemTypeEntry $entry): void {
		$dictionary = TypeConverter::getInstance()->getItemTypeDictionary();
		$reflection = new ReflectionClass($dictionary);

		$intToString = $reflection->getProperty("intToStringIdMap");
		/** @var int[] $value */
		$value = $intToString->getValue($dictionary);
		$intToString->setValue($dictionary, $value + [$itemId => $identifier]);

		$stringToInt = $reflection->getProperty("stringToIntMap");
		/** @var int[] $value */
		$value = $stringToInt->getValue($dictionary);
		$stringToInt->setValue($dictionary, $value + [$identifier => $itemId]);

		$itemTypes = $reflection->getProperty("itemTypes");
		$value = $itemTypes->getValue($dictionary);
		$value[] = $entry;
		$itemTypes->setValue($dictionary, $value);
	}

	/**
	 * Registers the required mappings for the block to become an item that can be placed etc. It is assigned an ID that
	 * correlates to its block ID.
	 */
	public function registerBlockItem(string $identifier, Block $block): void {
		$itemId = $block->getIdInfo()->getBlockTypeId();
		StringToItemParser::getInstance()->registerBlock($identifier, fn() => clone $block);
		$this->itemTableEntries[] = $entry = new ItemTypeEntry($identifier, $itemId, false, 2, new CacheableNbt(CompoundTag::create()));
		$this->registerCustomItemMapping($identifier, $itemId, $entry);

		$blockItemIdMap = BlockItemIdMap::getInstance();
		$reflection = new ReflectionClass($blockItemIdMap);

		$itemToBlockId = $reflection->getProperty("itemToBlockId");
		/** @var string[] $value */
		$value = $itemToBlockId->getValue($blockItemIdMap);
		$itemToBlockId->setValue($blockItemIdMap, $value + [$identifier => $identifier]);
	}
}
