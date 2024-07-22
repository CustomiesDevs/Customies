<?php

namespace customiesdevs\customies\block\component;

use pocketmine\nbt\tag\CompoundTag;

class GeometryComponent implements BlockComponent {

	private string $geometry;

	public function __construct(string $geometry) {
		$this->geometry = $geometry;
	}

	public function getName(): string {
		return "minecraft:geometry";
	}

	public function getValue(): CompoundTag {
		return CompoundTag::create()
			->setString("identifier", $this->geometry);
	}
}