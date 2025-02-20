<?php

namespace customiesdevs\customies\block\component;

use pocketmine\nbt\tag\CompoundTag;

class DestructibleByMiningComponent implements BlockComponent {

	private float $secondsToDestroy;

	/**
	 * Describes the destructible by mining properties for this block. If set to true, the block will take the default number of seconds to destroy. If set to false, this block is indestructible by mining. If the component is omitted, the block will take the default number of seconds to destroy.
	 * @param float $secondsToDestroy Sets the number of seconds it takes to destroy the block with base equipment. Greater numbers result in greater mining times.
	 */
	public function __construct(float $secondsToDestroy = 0.0) {
		$this->secondsToDestroy = $secondsToDestroy;
	}

	public function getName(): string {
		return "minecraft:destructible_by_mining";
	}

	public function getValue(): CompoundTag {
		return CompoundTag::create()
			->setFloat("seconds_to_destroy", $this->secondsToDestroy);
	}
}