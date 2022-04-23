<?php
declare(strict_types=1);

namespace customies\item;

use pocketmine\nbt\tag\CompoundTag;

interface ItemComponents {

	public function getComponents(): CompoundTag;
}