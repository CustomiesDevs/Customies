<?php

namespace customiesdevs\customies\block\component;

use pocketmine\nbt\tag\CompoundTag;

class DestructibleByExplosionComponent implements BlockComponent {

	private float $explosionResistance;

	/**
	 * Describes the destructible by explosion properties for this block. If set to true, the block will have the default explosion resistance. If set to false, this block is indestructible by explosion. If the component is omitted, the block will have the default explosion resistance.
	 * @param float $explosionResistance Sets the explosion resistance for the block. Greater values result in greater resistance to explosions. The scale will be different for different explosion power levels. A negative value or 0 means it will easily explode; larger numbers increase level of resistance.
	 */
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