<?php
declare(strict_types=1);

namespace customiesdevs\customies\Utils;

use customiesdevs\customies\Utils\CreativeInventoryInfo;use pocketmine\inventory\CreativeCategory;
use pocketmine\inventory\CreativeGroup;
use pocketmine\inventory\CreativeInventory;
use pocketmine\item\Item;
use pocketmine\lang\Translatable;
use pocketmine\utils\SingletonTrait;

final class CreativeItemManager{
	use SingletonTrait;
	private array $groups = [];

	public function __construct(){
		CreativeInventory::getInstance()->getContentChangedCallbacks()->add(function() : void{
		});
	}

	private function loadGroups() : void{
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

	public function AddItemOnGroup(Item $item, CreativeInventoryInfo $creativeInfo) : void{
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
        if ($creativeInfo->getCategory() === CreativeInventoryInfo::NONE) {
            return;
        }
		$category = match ($creativeInfo->getCategory()) { //wait, can we add existing groups in different categories here?
			CreativeInventoryInfo::CATEGORY_CONSTRUCTION => CreativeCategory::CONSTRUCTION,
			CreativeInventoryInfo::CATEGORY_ITEMS => CreativeCategory::ITEMS,
			CreativeInventoryInfo::CATEGORY_NATURE => CreativeCategory::NATURE,
			CreativeInventoryInfo::CATEGORY_EQUIPMENT => CreativeCategory::EQUIPMENT,
            /*,
			default => throw new AssumptionFailedError("Unknown category")*/
		};
        CreativeInventory::getInstance()->remove($item);
        CreativeInventory::getInstance()->add($item, $category, $group);
    }
}