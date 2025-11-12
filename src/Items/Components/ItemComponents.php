<?php
declare(strict_types=1);


namespace customiesdevs\customies\Items\Components;

use customiesdevs\customies\Items\Components\ItemComponentBase;use pocketmine\nbt\tag\CompoundTag;

interface ItemComponents {

    public function addComponent(ItemComponentBase $component): void;

    public function hasComponent(string $name): bool;

    public function getComponents(): CompoundTag;
}
