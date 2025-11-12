<?php

namespace customiesdevs\customies\Items;

use pocketmine\block\VanillaBlocks;
use pocketmine\data\bedrock\item\BlockItemIdMap;
use pocketmine\data\bedrock\item\SavedItemData;
use pocketmine\item\Item;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemTypeIds;
use pocketmine\item\StringToItemParser;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\convert\TypeConverter;
use pocketmine\network\mcpe\protocol\types\CacheableNbt;
use pocketmine\network\mcpe\protocol\types\ItemTypeEntry;
use pocketmine\utils\SingletonTrait;
use pocketmine\utils\Utils;
use pocketmine\world\format\io\GlobalItemDataHandlers;
use ReflectionClass;
use customiesdevs\customies\Items\Components\ItemComponents;

class CustomiesItemFactory {
    use SingletonTrait;

    public array $items = [];
    public array $itemTableEntries = [];
    private int $count = 0;

    public function getItemTableEntries(): array {
        return array_values($this->itemTableEntries);
    }

    public function getAll(): array
    {
        return $this->items;
    }

    public function get(string $identifier): Item
    {
        $item = StringToItemParser::getInstance()->parse($identifier);
        if ($item === null) {
            return VanillaBlocks::DIRT()->asItem();
        }
        return $item;
    }

    public function registerItem(string $className, string $identifier, string $name): void {
        if($className !== Item::class) {
            Utils::testValidInstance($className, Item::class);
        }

        $itemId = ItemTypeIds::newId();
        $itemIdentifier = new ItemIdentifier($itemId);

        $item = new $className($itemIdentifier, $name);

        /*if ($item instanceof Tool && $item instanceof ItemComponents) {
            $item->addComponent(new HandEquippedComponent(true));
        }*/

        $nbt = ($componentBased = $item instanceof ItemComponents) ? $item->getComponents()
            ->setInt("id", $itemId)
            ->setString("name", $identifier) : CompoundTag::create();

        $this->itemTableEntries[$identifier] = $entry = new ItemTypeEntry($identifier, $itemId, $componentBased, $componentBased ? 1 : 0, new CacheableNbt($nbt));
        $this->registerCustomItemMapping($entry);

        GlobalItemDataHandlers::getDeserializer()->map($identifier, fn() => clone $item);
        GlobalItemDataHandlers::getSerializer()->map($item, fn() => new SavedItemData($identifier));

        StringToItemParser::getInstance()->register($identifier, fn() => clone $item);

        //Servers::$customItems[] = $item;
        $this->items[] = $item;

        $this->count++;
    }

    public function registerCustomItemMapping(ItemTypeEntry $itemTypeEntry) : void {
        $dictionary = TypeConverter::getInstance()->getItemTypeDictionary();
        $reflection = new ReflectionClass($dictionary);

        $intToString = $reflection->getProperty("intToStringIdMap");
        /** @var int[] $value */
        $value = $intToString->getValue($dictionary);
        $intToString->setValue($dictionary, $value + [$itemTypeEntry->getNumericId() => $itemTypeEntry->getStringId()]);

        $stringToInt = $reflection->getProperty("stringToIntMap");
        /** @var int[] $value */
        $value = $stringToInt->getValue($dictionary);
        $stringToInt->setValue($dictionary, $value + [$itemTypeEntry->getStringId() => $itemTypeEntry->getNumericId()]);
        $itemTypesProperty = $reflection->getProperty('itemTypes');
        $itemTypesProperty->setValue($dictionary, array_merge($itemTypesProperty->getValue($dictionary), [$itemTypeEntry]));
    }

    public function getRegisteredItemCount(): int {
        return $this->count;
    }

    public function registerBlockItem(ItemTypeEntry $entry): void {
        $this->registerCustomItemMapping($entry);
        $blockItemIdMap = BlockItemIdMap::getInstance();
        $reflection = new \ReflectionClass($blockItemIdMap);

        $itemToBlockId = $reflection->getProperty("itemToBlockId");
        /** @var string[] $value */
        $value = $itemToBlockId->getValue($blockItemIdMap);
        $itemToBlockId->setValue($blockItemIdMap, $value + [$entry->getStringId() => $entry->getStringId()]);
    }
}