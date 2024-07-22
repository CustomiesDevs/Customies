<?php

namespace customiesdevs\customies\block\component;

use pocketmine\nbt\tag\CompoundTag;

class DestructibleByMiningComponent implements BlockComponent {

	private float $secondsToDestroy;

	public function __construct(float $secondsToDestroy = 0.0) {
		$this->secondsToDestroy = $secondsToDestroy;
	}

	public function getName(): string {
		return "minecraft:destructible_by_mining";
	}

	public function getValue(): CompoundTag {
		return CompoundTag::create()
			->setFloat("value", $this->secondsToDestroy);
	}
}