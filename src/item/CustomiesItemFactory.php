<?php
declare(strict_types=1);

namespace customies\item;

use InvalidArgumentException;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIdentifier;
use pocketmine\network\mcpe\convert\ItemTranslator;
use pocketmine\network\mcpe\protocol\types\CacheableNbt;
use pocketmine\network\mcpe\protocol\types\ItemComponentPacketEntry;
use pocketmine\network\mcpe\protocol\types\ItemTypeEntry;
use pocketmine\utils\Utils;
use ReflectionClass;
use ReflectionException;
use RuntimeException;
use function count;

class CustomiesItemFactory {

	/**
	 * @var Item[]
	 * @phpstan-var array<string, Item>
	 */
	private static array $customItems = [];
	/**
	 * @var ItemTypeEntry[]
	 */
	private static array $itemTableEntries = [];
	/**
	 * @var ItemComponentPacketEntry[]
	 * @phpstan-var array<string, ItemComponentPacketEntry>
	 */
	private static array $cachedItemProperties = [];

	/**
	 * Get a custom item from its identifier. an exception will be thrown if the item is not registered.
	 *
	 * @param string $identifier
	 * @param int    $amount
	 *
	 * @return Item
	 */
	public static function get(string $identifier, int $amount): Item {
		$id = -1;
		foreach(self::$itemTableEntries as $entry){
			if($entry->getStringId() === $identifier) {
				$id = $entry->getNumericId();
			}
		}
		if($id < 0) {
			throw new InvalidArgumentException("Custom item " . $identifier . " is not registered");
		}

		return ItemFactory::getInstance()->get($id, 0, $amount);
	}

	/**
	 * Returns the item properties CompoundTag which maps out all custom item properties.
	 *
	 * @return array
	 */
	public static function getCachedItemProperties(): array {
		return self::$cachedItemProperties;
	}

	/**
	 * Returns custom item entries for the StartGamePacket itemTable property.
	 *
	 * @return int[]
	 * @phpstan-return array<string, int>
	 */
	public static function getItemTableEntries(): array {
		return self::$itemTableEntries;
	}

	/**
	 * Register an item to the ItemFactory and all the required mappings.
	 *
	 * @param string $className
	 * @param string $identifier
	 * @param string $name
	 * @throws ReflectionException
	 */
	public static function registerItem(string $className, string $identifier, string $name): void {
		if($className !== Item::class) {
			Utils::testValidInstance($className, Item::class);
		}

		/** @var Item $item */
		$item = new $className(new ItemIdentifier(950 + count(self::$customItems), 0), $name);

		if(ItemFactory::getInstance()->isRegistered($item->getId())) {
			throw new RuntimeException("Block with ID " . $item->getId() . " is already registered");
		}
		self::registerCustomItemMapping($item->getId());
		ItemFactory::getInstance()->register($item);
		$componentBased = isset(class_uses($item)[ItemComponentsTrait::class]);
		if($componentBased) {
			/** @var ItemComponentsTrait $item */
			$componentsTag = $item->getComponents();
			$componentsTag->setInt("id", $item->getId());
			$componentsTag->setString("name", $identifier);
			self::$cachedItemProperties[$identifier] = new ItemComponentPacketEntry($identifier, new CacheableNbt($componentsTag));
		}
		self::$customItems[$identifier] = $item;
		self::$itemTableEntries[] = new ItemTypeEntry($identifier, $item->getId(), $componentBased);
	}

	/**
	 * Register a custom item id to the global ItemTranslator instance.
	 * @param int $id
	 * @return void
	 * @throws ReflectionException
	 */
	public static function registerCustomItemMapping(int $id): void {
		$translator = ItemTranslator::getInstance();
		$reflection = new ReflectionClass($translator);

		$reflectionProperty = $reflection->getProperty("simpleCoreToNetMapping");
		$reflectionProperty->setAccessible(true);
		$reflectionProperty->setValue($translator, $reflectionProperty->getValue($translator) + [$id => $id]);

		$reflectionProperty = $reflection->getProperty("simpleNetToCoreMapping");
		$reflectionProperty->setAccessible(true);
		$reflectionProperty->setValue($translator, $reflectionProperty->getValue($translator) + [$id => $id]);
	}

	public static function addItemTypeEntry(ItemTypeEntry $entry): void {
		self::$itemTableEntries[] = $entry;
	}
}
