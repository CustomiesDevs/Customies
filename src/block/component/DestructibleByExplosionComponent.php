<?php

namespace customiesdevs\customies\block\component;

use pocketmine\nbt\tag\CompoundTag;

class DestructibleByExplosionComponent implements BlockComponent {

	private float $explosionResistance;

	public function __construct(float $explosionResistance = 0.0) {
		$this->explosionResistance = $explosionResistance;
	}

	public function getName(): string {
		return "minecraft:destructible_by_explosion";
	}

	public function getValue(): CompoundTag {
		return CompoundTag::create()
			->setFloat("explosion_resistance", $this->explosionResistance);
	}
}