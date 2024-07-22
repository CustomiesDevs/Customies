<?php

namespace customiesdevs\customies\block\component;

use pocketmine\nbt\tag\CompoundTag;

class DisplayNameComponent implements BlockComponent {

	private string $displayName;

	public function __construct(string $displayName) {
		$this->displayName = $displayName;
	}

	public function getName(): string {
		return "minecraft:display_name";
	}

	public function getValue(): CompoundTag {
		return CompoundTag::create()
			->setString("value", $this->displayName);
	}
}