<?php
declare(strict_types=1);

namespace customiesdevs\customies\item;

use customiesdevs\customies\util\Cache;
use InvalidArgumentException;
use pocketmine\block\Block;
use pocketmine\data\bedrock\item\SavedItemData;
use pocketmine\inventory\CreativeInventory;
use pocketmine\item\Item;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\StringToItemParser;
use pocketmine\network\mcpe\convert\GlobalItemTypeDictionary;
use pocketmine\network\mcpe\protocol\types\CacheableNbt;
use pocketmine\network\mcpe\protocol\types\ItemComponentPacketEntry;
use pocketmine\network\mcpe\protocol\types\ItemTypeEntry;
use pocketmine\utils\SingletonTrait;
use pocketmine\utils\Utils;
use pocketmine\world\format\io\GlobalItemDataHandlers;
use ReflectionClass;
use function array_values;

final class CustomiesItemFactory {

	use SingletonTrait;

	/**
	 * @var ItemTypeEntry[]
	 */
	private array $itemTableEntries = [];
	/**
	 * @var ItemComponentPacketEntry[]
	 */
	private array $itemComponentEntries = [];

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

	/**
	 * Returns the item properties CompoundTag which maps out all custom item properties.
	 * @return ItemComponentPacketEntry[]
	 */
	public function getItemComponentEntries(): array {
		return $this->itemComponentEntries;
	}

	/**
	 * Returns custom item entries for the StartGamePacket itemTable property.
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
	public function registerItem(string $className, string $identifier, string $name): void {
		if($className !== Item::class) {
			Utils::testValidInstance($className, Item::class);
		}

		$itemId = Cache::getInstance()->getNextAvailableItemID($identifier);
		$item = new $className(new ItemIdentifier($itemId), $name);
		$this->registerCustomItemMapping($item, $identifier, $itemId);

		if(($componentBased = $item instanceof ItemComponents)) {
			$componentsTag = $item->getComponents();
			$componentsTag->setInt("id", $itemId);
			$componentsTag->setString("name", $identifier);
			$this->itemComponentEntries[$identifier] = new ItemComponentPacketEntry($identifier, new CacheableNbt($componentsTag));
		}

		$this->itemTableEntries[$identifier] = new ItemTypeEntry($identifier, $itemId, $componentBased);
		CreativeInventory::getInstance()->add($item);
	}

	/**
	 * Registers a custom item ID to the required mappings in the global ItemTypeDictionary instance.
	 */
	private function registerCustomItemMapping(Item $item, string $name, int $id): void {
		$dictionary = GlobalItemTypeDictionary::getInstance()->getDictionary();
		$reflection = new ReflectionClass($dictionary);

		$intToString = $reflection->getProperty("intToStringIdMap");
		$intToString->setAccessible(true);
		/** @var int[] $value */
		$value = $intToString->getValue($dictionary);
		$intToString->setValue($dictionary, $value + [$id => $name]);

		$stringToInt = $reflection->getProperty("stringToIntMap");
		$stringToInt->setAccessible(true);
		/** @var int[] $value */
		$value = $stringToInt->getValue($dictionary);
		$stringToInt->setValue($dictionary, $value + [$name => $id]);

		GlobalItemDataHandlers::getDeserializer()->map($name, fn() => clone $item);
		GlobalItemDataHandlers::getSerializer()->map($item, fn() => new SavedItemData($name));
		StringToItemParser::getInstance()->register($name, fn() => clone $item);
	}

	/**
	 * Registers the required mappings for the block to become an item that can be placed etc. It is assigned an ID that
	 * correlates to its block ID.
	 */
	public function registerBlockItem(string $identifier, Block $block): void {
		$itemId = $block->getIdInfo()->getBlockTypeId();
		//$this->registerCustomItemMapping($identifier, $itemId);
		$this->itemTableEntries[] = new ItemTypeEntry($identifier, $itemId, false);
	}
}
