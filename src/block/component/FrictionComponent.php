<?php

namespace customiesdevs\customies\block\component;

use pocketmine\nbt\tag\CompoundTag;

class FrictionComponent implements BlockComponent {

	private float $friction;

	/**
	 * Describes the friction for this block in a range of `0.0` to `0.9`. Friction affects an entity's movement speed when it travels on the block. Greater value results in more friction.
	 * For context, wood and dirt are set to a friction of `0.4` while ice is set to `0.02`.
	 * @param float $friction
	 */
	public function __construct(float $friction) {
		$this->friction = $friction;
	}

	public function getName(): string {
		return "minecraft:friction";
	}

	public function getValue(): CompoundTag {
		return CompoundTag::create()
			->setFloat("value", $this->friction);
	}
}