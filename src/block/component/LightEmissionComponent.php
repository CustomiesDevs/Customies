<?php

namespace customiesdevs\customies\block\component;

use pocketmine\nbt\tag\CompoundTag;

class LightEmissionComponent implements BlockComponent {

	private int $emission;

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