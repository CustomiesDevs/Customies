<?php

namespace customiesdevs\customies\block\component;

use pocketmine\nbt\tag\CompoundTag;

class BreathabilityComponent implements BlockComponent {

	private string $breathability;

	public const SOLID = "solid";
	public const AIR = "air";

	/**
	 * Determines whether the block is breathable by defining if the block is treated as a `solid` or as `air`. The default is `solid` if this component is omitted
	 * @param string $breathability state of the block
	 */
	public function __construct(string $breathability = self::SOLID) {
		$this->breathability = $breathability;
	}

	public function getName(): string {
		return "minecraft:breathability";
	}

	public function getValue(): CompoundTag {
		return CompoundTag::create()
			->setString("value", $this->breathability);
	}
}