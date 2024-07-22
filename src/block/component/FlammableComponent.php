<?php

namespace customiesdevs\customies\block\component;

use pocketmine\nbt\tag\CompoundTag;

class FlammableComponent implements BlockComponent {

	private int $catchChanceModifier;
	private int $destroyChanceModifier;

	public function __construct(int $catchChanceModifier = 5, int $destroyChanceModifier = 20) {
		$this->catchChanceModifier = $catchChanceModifier;
		$this->destroyChanceModifier = $destroyChanceModifier;
	}

	public function getName(): string {
		return "minecraft:display_name";
	}

	public function getValue(): CompoundTag {
		return CompoundTag::create()
			->setInt("catch_chance_modifier", $this->catchChanceModifier)
			->setInt("destroy_chance_modifier", $this->destroyChanceModifier);
	}
}