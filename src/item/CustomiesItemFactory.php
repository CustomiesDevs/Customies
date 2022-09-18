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
use function asort;

final class CustomiesItemFactory {
	use SingletonTrait;

    private int $nextItemID = 950;
    private bool $caching = false;

    /**
     * @var array
     * itemIdCache holds a string identifier to item id map used when item-id-caching is enabled in the config.
     */
	private array $itemIdCache = [];

	/**
	 * @var ItemTypeEntry[]
	 */
	private array $itemTableEntries = [];
	/**
	 * @var ItemComponentPacketEntry[]
	 */
	private array $itemComponentEntries = [];

    /**
     * @param array $cache
     */
	public function initCache(array $cache): void {
	    if (!$this->caching) {
            $this->caching = true;
            asort($this->itemIdCache);
            $this->itemIdCache = $cache;
        }
    }

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
     * Returns the cache of string identifiers to item ids used for inter-runtime id saving.
     * @return array
     */
	public function getItemIdCache(): array {
	    return $this->itemIdCache;
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
		$item = new $className(new ItemIdentifier($this->getNextAvailableId($identifier), 0), $name);

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

    /**
     * getNextAvailableId retrieves the lowest unregistered item id above 950. It will check the item id cache if caching is
     * enabled.
     */
	private function getNextAvailableId(string $identifier): int {
        if($this->caching){
            // if the item is already cached then return the cached item id.
            if (isset($this->itemIdCache[$identifier])) {
                $id = $this->itemIdCache[$identifier];
            } else {
                $id = ++$this->nextItemID;
                $previous = null;
                foreach ($this->itemIdCache as $key => $value) {
                    if ($value > $id) {
                        if ($previous !== null) {
                            $id = $this->itemIdCache[$previous]+1;
                            // if the id already exists increment by one and keep looking
                            if ($id === $value){
                                $id += 1;
                                continue;
                            }
                            $this->itemIdCache[$identifier] = $id;
                            break;
                        }
                        $this->nextItemID = $id;
                    }
                    $previous = $key;
                }
                // we do this on the off chance that the id matches the greatest id inside of the cache.
                if($this->itemIdCache[$previous] === $id){
                    $id += 1;
                    $this->itemIdCache[$identifier] = $id;
                    $this->nextItemID = $id;
                }
                asort($this->itemIdCache);
            }
        }else{
            // if we're not caching then just get the item id using the normal means.
            $id = ++$this->nextItemID;
        }
        return $id;
    }
}
