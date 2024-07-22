<?php

namespace customiesdevs\customies\block\component;

use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\ListTag;

class SelectionBoxComponent implements BlockComponent {

	private Vector3 $origin;
	private Vector3 $size;

	public function __construct(Vector3 $origin = new Vector3(-8.0, 0.0, -8.0), Vector3 $size = new Vector3(16.0, 16.0, 16.0)) {
		$this->origin = $origin;
		$this->size = $size;
	}

	public function getName(): string {
		return "minecraft:selection_box";
	}

	public function getValue(): CompoundTag {
		return CompoundTag::create()
			->setByte("enabled", 1)
			->setTag("origin", new ListTag([
				new FloatTag($this->origin->getX()),
				new FloatTag($this->origin->getY()),
				new FloatTag($this->origin->getZ())
			]))
			->setTag("size", new ListTag([
				new FloatTag($this->size->getX()),
				new FloatTag($this->size->getY()),
				new FloatTag($this->size->getZ())
			]));
	}
}