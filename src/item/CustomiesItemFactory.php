<?php
declare(strict_types=1);

namespace customiesdevs\customies\item;

use pocketmine\block\Block;
use pocketmine\inventory\CreativeInventory;
use pocketmine\item\{Item, ItemIdentifier, StringToItemParser};
use pocketmine\network\mcpe\{
    convert\GlobalItemTypeDictionary, protocol\types\CacheableNbt, protocol\types\ItemComponentPacketEntry,
    protocol\types\ItemTypeEntry
};
use pocketmine\utils\{SingletonTrait, Utils};
use pocketmine\world\format\io\GlobalItemDataHandlers;
use pocketmine\data\bedrock\item\SavedItemData;

use customiesdevs\customies\util\Cache;

use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;
use function array_values;

final class CustomiesItemFactory {

	use SingletonTrait;

	/** @var ItemTypeEntry[] $itemTableEntries */
	private array $itemTableEntries = [];

	/** @var ItemComponentPacketEntry[] $itemComponentEntries */
	private array $itemComponentEntries = [];

    /**
     * Get a custom item from its identifier. An exception will be thrown if the item is not registered.
     *
     * @param string $identifier
     * @param int $amount
     * @return Item
     */
	public function get(string $identifier, int $amount = 1): Item {

		$item = StringToItemParser::getInstance()->parse($identifier);

		if($item === null)
            throw new InvalidArgumentException("Custom item " . $identifier . " is not registered");

		return $item->setCount($amount);
	}

	/**
	 * Returns the item properties CompoundTag which maps out all custom item properties.
     *
	 * @return ItemComponentPacketEntry[]
	 */
	public function getItemComponentEntries(): array {
		return $this->itemComponentEntries;
	}

	/**
	 * Returns custom item entries for the StartGamePacket itemTable property.
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
     * @param string $className
     * @param string $identifier
     * @param string $name
     * @return void
     *
     * @phpstan-param class-string $className
     * @throws ReflectionException
     */
	public function registerItem(string $className, string $identifier, string $name): void {
		if($className !== Item::class)
            Utils::testValidInstance($className, Item::class);

		$itemId = Cache::getInstance()->getNextAvailableItemID($identifier);
		$item = new $className(new ItemIdentifier($itemId), $name);
		$this->registerCustomItemMapping($identifier, $itemId);

		GlobalItemDataHandlers::getDeserializer()->map($identifier, fn() => clone $item);
		GlobalItemDataHandlers::getSerializer()->map($item, fn() => new SavedItemData($name));

		StringToItemParser::getInstance()->register($identifier, fn() => clone $item);
		CreativeInventory::getInstance()->add($item);

		if(($componentBased = $item instanceof ItemComponents))
			$this->itemComponentEntries[$identifier] = new ItemComponentPacketEntry($identifier,
				new CacheableNbt($item->getComponents()
					->setInt("id", $itemId)
					->setString("name", $identifier)
				)
			);

		$this->itemTableEntries[$identifier] = new ItemTypeEntry($identifier, $itemId, $componentBased);
	}

    /**
     * Registers a custom item ID to the required mappings in the global ItemTypeDictionary instance.
     *
     * @param string $identifier
     * @param int $itemId
     * @return void
     * @throws ReflectionException
     */
	private function registerCustomItemMapping(string $identifier, int $itemId): void {

		$dictionary = GlobalItemTypeDictionary::getInstance()->getDictionary();
		$reflection = new ReflectionClass($dictionary);

		$intToString = $reflection->getProperty("intToStringIdMap");
		$intToString->setAccessible(true);
		/** @var int[] $value */
		$value = $intToString->getValue($dictionary);
		$intToString->setValue($dictionary, $value + [$itemId => $identifier]);

		$stringToInt = $reflection->getProperty("stringToIntMap");
		$stringToInt->setAccessible(true);

		/** @var int[] $value */
		$value = $stringToInt->getValue($dictionary);
		$stringToInt->setValue($dictionary, $value + [$identifier => $itemId]);

	}

    /**
     * Registers the required mappings for the block to become an item that can be placed etc. It is assigned an ID that
     * correlates to its block ID.
     *
     * @param string $identifier
     * @param Block $block
     * @return void
     * @throws ReflectionException
     */
	public function registerBlockItem(string $identifier, Block $block): void {
		$itemId = $block->getIdInfo()->getBlockTypeId();
		$this->registerCustomItemMapping($identifier, $itemId);

		StringToItemParser::getInstance()->registerBlock($identifier, fn() => clone $block);

		$this->itemTableEntries[] = new ItemTypeEntry($identifier, $itemId, false);
	}
}
