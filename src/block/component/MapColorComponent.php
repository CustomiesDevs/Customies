<?php

namespace customiesdevs\customies\block\component;

use pocketmine\nbt\tag\CompoundTag;

class MapColorComponent implements BlockComponent {

	private string $hexString;

	public function __construct(string $hexString) {
		$this->hexString = $hexString;
	}

	public function getName(): string {
		return "minecraft:map_color";
	}

	public function getValue(): CompoundTag {
		return CompoundTag::create()
			->setString("value", $this->hexString);
	}
}