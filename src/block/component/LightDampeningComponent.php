<?php

namespace customiesdevs\customies\block\component;

use pocketmine\nbt\tag\CompoundTag;

class LightDampeningComponent implements BlockComponent {

	private int $dampening;

	/**
	 * The amount that light will be dampened when it passes through the block, in a range (0-15). Higher value means the light will be dampened more.
	 * @param int $dampening
	 */
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