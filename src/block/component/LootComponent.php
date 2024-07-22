<?php

namespace customiesdevs\customies\block\component;

use pocketmine\nbt\tag\CompoundTag;

class LootComponent implements BlockComponent {

	private string $pathString;

	public function __construct(string $pathString) {
		$this->pathString = $pathString;
	}

	public function getName(): string {
		return "minecraft:loot";
	}

	public function getValue(): CompoundTag {
		return CompoundTag::create()
			->setString("value", $this->pathString);
	}
}