<?php

namespace customiesdevs\customies\Items;

use pocketmine\item\Armor;

abstract class CustomArmor extends Armor {

    public function getToughness(): int
    {
        return 0;
    }
}