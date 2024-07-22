<?php

namespace customiesdevs\customies\block\component;

use pocketmine\nbt\tag\CompoundTag;

class LightDampeningComponent implements BlockComponent {

	private int $dampening;

	public function __construct(int $dampening = 15) {
		$this->dampening = $dampening;
	}

	public function getName(): string {
		return "minecraft:light_dampening";
	}

	public function getValue(): CompoundTag {
		return CompoundTag::create()
			->setByte("lightLevel", $this->dampening);
	}
}