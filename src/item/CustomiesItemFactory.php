<?php
declare(strict_types=1);

namespace customiesdevs\customies\item;

use InvalidArgumentException;
use pocketmine\inventory\CreativeInventory;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIdentifier;
use pocketmine\network\mcpe\convert\ItemTranslator;
use pocketmine\network\mcpe\protocol\types\CacheableNbt;
use pocketmine\network\mcpe\protocol\types\ItemComponentPacketEntry;
use pocketmine\network\mcpe\protocol\types\ItemTypeEntry;
use pocketmine\utils\SingletonTrait;
use pocketmine\utils\Utils;
use ReflectionClass;
use RuntimeException;
use function array_values;

final class CustomiesItemFactory {
	use SingletonTrait;

	private int $nextItemID = 950;
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
		$id = $this->itemTableEntries[$identifier]?->getNumericId();
		if($id === null) {
			throw new InvalidArgumentException("Custom item " . $identifier . " is not registered");
		}
		return ItemFactory::getInstance()->get($id, 0, $amount);
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
		/** @var Item $item */
		$item = new $className(new ItemIdentifier(++$this->nextItemID, 0), $name);

		if(ItemFactory::getInstance()->isRegistered($item->getId())) {
			throw new RuntimeException("Item with ID " . $item->getId() . " is already registered");
		}
		$this->registerCustomItemMapping($item->getId());
		ItemFactory::getInstance()->register($item);

		if(($componentBased = $item instanceof ItemComponents)) {
			$componentsTag = $item->getComponents();
			$componentsTag->setInt("id", $item->getId());
			$componentsTag->setString("name", $identifier);
			$this->itemComponentEntries[$identifier] = new ItemComponentPacketEntry($identifier, new CacheableNbt($componentsTag));
		}

		$this->itemTableEntries[$identifier] = new ItemTypeEntry($identifier, $item->getId(), $componentBased);
		CreativeInventory::getInstance()->add($item);
	}

	/**
	 * Registers a custom item ID to the required mappings in the ItemTranslator instance.
	 */
	private function registerCustomItemMapping(int $id): void {
		$translator = ItemTranslator::getInstance();
		$reflection = new ReflectionClass($translator);

		$reflectionProperty = $reflection->getProperty("simpleCoreToNetMapping");
		$reflectionProperty->setAccessible(true);
		/** @var int[] $value */
		$value = $reflectionProperty->getValue($translator);
		$reflectionProperty->setValue($translator, $value + [$id => $id]);

		$reflectionProperty = $reflection->getProperty("simpleNetToCoreMapping");
		$reflectionProperty->setAccessible(true);
		/** @var int[] $value */
		$value = $reflectionProperty->getValue($translator);
		$reflectionProperty->setValue($translator, $value + [$id => $id]);
	}

	/**
	 * Registers the required mappings for the block to become an item that can be placed etc. It is assigned an ID that
	 * correlates to its block ID.
	 */
	public function registerBlockItem(string $identifier, int $blockId): void {
		$itemId = 255 - $blockId;
		$this->registerCustomItemMapping($itemId);
		$this->itemTableEntries[] = new ItemTypeEntry($identifier, $itemId, false);
	}
}
