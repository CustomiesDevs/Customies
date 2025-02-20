<?php

namespace customiesdevs\customies\block\component;

use pocketmine\nbt\tag\CompoundTag;

class LightEmissionComponent implements BlockComponent {

	private int $emission;

	/**
	 * The amount of light this block will emit in a range (0-15). Higher value means more light will be emitted.
	 * @param int $emission
	 */
	public function __construct(int $emission = 0) {
		$this->emission = $emission;
	}

	public function getName(): string {
		return "minecraft:light_emission";
	}

	public function getValue(): CompoundTag {
		return CompoundTag::create()
			->setByte("emission", $this->emission);
	}
}