<?php
declare(strict_types=1);

namespace customiesdevs\customies\item;

use Closure;
use customiesdevs\customies\util\NBT;
use InvalidArgumentException;
use pocketmine\block\Block;
use pocketmine\data\bedrock\item\BlockItemIdMap;
use pocketmine\data\bedrock\item\SavedItemData;
use pocketmine\item\Item;
use pocketmine\item\StringToItemParser;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\convert\TypeConverter;
use pocketmine\network\mcpe\protocol\types\CacheableNbt;
use pocketmine\network\mcpe\protocol\types\ItemTypeEntry;
use pocketmine\utils\SingletonTrait;
use pocketmine\world\format\io\GlobalItemDataHandlers;
use ReflectionClass;
use function array_values;

final class CustomiesItemFactory {
	use SingletonTrait;

	/** Default values for item_properties */
	private const PROPERTY_DEFAULTS = [
		'allow_off_hand' => false, // Byte
		'can_destroy_in_creative' => true, // Byte
		'damage' => 0, // Int
		'enchantable_slot' => 'none', // String
		'enchantable_value' => 0, // Int
		'foil' => false, // Byte
		'frame_count' => 1, // Int
		'hand_equipped' => false, // Byte
		'liquid_clipped' => false, // Byte
		'max_stack_size' => 64, // Int
		'mining_speed' => 1.0, // Float
		'should_despawn' => true, // Byte
		'stacked_by_data' => false, // Byte
		'use_animation' => 0, // Int
		'use_duration' => 0, // Int
	];

	/** Order in which properties should appear in item_properties */
	private const PROPERTY_ORDER = [
		'allow_off_hand', // Byte
		'can_destroy_in_creative', // Byte
		'creative_category', // Int
		'creative_group', // String
		'damage', // Int
		'enchantable_slot', // String
		'enchantable_value', // Int
		'foil', // Byte
		'frame_count', // Int
		'hand_equipped', // Byte
		'hidden_in_commands', // Byte
		'hover_text_color', // String
		'liquid_clipped', // Byte
		'max_stack_size', // Int
		'minecraft:icon', // String
		'mining_speed', // Float
		'should_despawn', // Byte
		'stacked_by_data', // Byte
		'use_animation', // Int
		'use_duration', // Int
	];

	/** @var ItemTypeEntry[] */
	private array $itemTableEntries = [];

	/**
	 * Get a custom item from its identifier. An exception will be thrown if the item is not registered.
	 * 
	 * @param string $identifier The string identifier for the item, usually in the format "namespace:item_name"
	 * @param int $amount The amount of the item to be returned
	 * @return Item The item instance
	 * @throws InvalidArgumentException if the item is not registered
	 */
	public function get(string $identifier, int $amount = 1): Item {
		$item = StringToItemParser::getInstance()->parse($identifier);
		if($item === null) {
			throw new InvalidArgumentException("Custom item " . $identifier . " is not registered");
		}
		return $item->setCount($amount);
	}

	/**
	 * Returns all registered item table entries.
	 * 
	 * @return ItemTypeEntry[]
	 */
	public function getItemTableEntries(): array {
		return array_values($this->itemTableEntries);
	}

	/**
	 * Registers the item to the item factory and assigns it an ID. It also updates the required mappings and stores the
	 * item components if present.
	 * 
	 * @param Closure $itemFunc A closure that returns an instance of the item to be registered
	 * @param string $identifier The string identifier for the item, usually in the format "namespace:item_name"
	 * @param CreativeInventoryInfo $creativeInfo Creative inventory information for the item. Default set to `Equipment` Category.
	 * @throws InvalidArgumentException if the closure does not return an Item instance
	 */
	public function registerItem(
		Closure $itemFunc, 
		string $identifier, 
		CreativeInventoryInfo $creativeInfo = new CreativeInventoryInfo(CreativeInventoryInfo::CATEGORY_EQUIPMENT)
	): void {
		$item = $itemFunc();
		if(!$item instanceof Item) {
			throw new InvalidArgumentException("Class returned from closure is not a Item");
		}
		$itemId = $item->getTypeId();

		GlobalItemDataHandlers::getDeserializer()->map($identifier, fn() => clone $item);
		GlobalItemDataHandlers::getSerializer()->map($item, fn() => new SavedItemData($identifier));
		StringToItemParser::getInstance()->register($identifier, fn() => clone $item);

		// Adding item components
		$componentBased = $item instanceof ItemComponents;
		// Registers the item to creative inventory
		CreativeInventoryInfo::registerCreativeInfo($item, $creativeInfo);
		// Create the NBT data for the item
		$nbt = $this->createItemNbt($item, $identifier, $itemId, $creativeInfo);
		$entry = new ItemTypeEntry(
			$identifier,
			$itemId,
			$componentBased,
			$componentBased ? 1 : 0,
			new CacheableNbt($nbt)
		);
		$this->itemTableEntries[$identifier] = $entry;
		$this->registerCustomItemMapping($identifier, $itemId, $entry);
	}

	/**
	 * Creates the CompoundTag for an item, including components and default properties.
	 */
	private function createItemNbt(Item $item, string $identifier, int $itemId, CreativeInventoryInfo $creativeInfo): CompoundTag {
		if(!($item instanceof ItemComponents)) {
			return CompoundTag::create();
		}
		// Initialize item_properties with defaults
		$propertiesTag = CompoundTag::create();
		foreach(self::PROPERTY_DEFAULTS as $name => $default) {
			$propertiesTag->setTag($name, NBT::getTagType($default));
		}
		// Set creative info
		$propertiesTag->setTag('creative_category', NBT::getTagType((int) $creativeInfo->getNumericCategory()));
		$propertiesTag->setTag('creative_group', NBT::getTagType((string) $creativeInfo->getGroup()));
		$propertiesTag->setByte("hidden_in_commands", 2);
		$tags = [];
		$componentsTag = CompoundTag::create();
		// Process each component
		foreach($item->getComponents() as $component) {
			$name = $component->getName();
			$value = $component->getValue();
			$tag = NBT::getTagType($value);
			// Icon goes to item_properties
			if($name === 'minecraft:icon') {
				$propertiesTag->setTag('minecraft:icon', $tag);
				continue;
			}
			// Tags go to item_tags
			if($name === 'minecraft:tags') {
				$tags = $value['tags'] ?? [];
				$componentsTag->setTag('minecraft:tags', $tag);
				continue;
			}
			// Components with property mappings also update item_properties
			$mapping = $component->getPropertyMapping();
			if($mapping !== null) {
				foreach($mapping as $prop => $propValue) {
					if($prop === "use_duration"){
						$propertiesTag->setTag("use_duration", NBT::getTagType((int) round($propValue * 20)));
						continue;
					}
					$propertiesTag->setTag($prop, NBT::getTagType($propValue));
				}
			}
			// All components go to components tag
			$componentsTag->setTag($name, $tag);
		}
		$propertiesTag = NBT::sortCompoundTag($propertiesTag, self::PROPERTY_ORDER);
		$components = CompoundTag::create()
			->setTag('item_properties', $propertiesTag)
			->setTag('item_tags', NBT::getTagType($tags))
			->merge($componentsTag);

		return CompoundTag::create()
			->setTag('components', $components)
			->setInt('id', $itemId)
			->setString('name', $identifier);
	}

	/**
	 * Registers a custom item ID to the required mappings in the global ItemTypeDictionary instance.
	 * This allows the item to be recognized and used within the game.
	 * @param string $identifier The string identifier for the item, usually in the format "namespace:item_name"
	 * @param int $itemId The numerical ID to be assigned to the item
	 * @param ItemTypeEntry $entry The ItemTypeEntry instance representing the item
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
	 * @param string $identifier The string identifier for the block item, usually in the format "namespace:block_name"
	 * @param Block $block The block instance to be registered as an item
	 */
	public function registerBlockItem(string $identifier, Block $block): void {
		$itemId = $block->getIdInfo()->getBlockTypeId();
		StringToItemParser::getInstance()->registerBlock($identifier, fn() => clone $block);
		$entry = new ItemTypeEntry($identifier, $itemId, false, 2, new CacheableNbt(CompoundTag::create()));
		$this->itemTableEntries[] = $entry;
		$this->registerCustomItemMapping($identifier, $itemId, $entry);

		$blockItemIdMap = BlockItemIdMap::getInstance();
		$reflection = new ReflectionClass($blockItemIdMap);

		$itemToBlockId = $reflection->getProperty("itemToBlockId");
		/** @var string[] $value */
		$value = $itemToBlockId->getValue($blockItemIdMap);
		$itemToBlockId->setValue($blockItemIdMap, $value + [$identifier => $identifier]);
	}
}