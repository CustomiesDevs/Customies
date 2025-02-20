<?php

namespace customiesdevs\customies\block\component;

use pocketmine\nbt\tag\CompoundTag;

class FlammableComponent implements BlockComponent {

	private int $catchChanceModifier;
	private int $destroyChanceModifier;

	/**
	 * Describes the flammable properties for this block. If set to true, default values are used. If set to false, or if this component is omitted, the block will not be able to catch on fire naturally from neighbors, but it can still be directly ignited.
	 * @param int $catchChanceModifier A modifier affecting the chance that this block will catch flame when next to a fire. Values are greater than or equal to 0, with a higher number meaning more likely to catch on fire. For a "catch_chance_modifier" greater than 0, the fire will continue to burn until the block is destroyed (or it will burn forever if the "destroy_chance_modifier" is 0). If the "catch_chance_modifier" is 0, and the block is directly ignited, the fire will eventually burn out without destroying the block (or it will have a chance to be destroyed if "destroy_chance_modifier" is greater than 0). The default value of 5 is the same as that of Planks.
	 * @param int $destroyChanceModifier A modifier affecting the chance that this block will be destroyed by flames when on fire. Values are greater than or equal to 0, with a higher number meaning more likely to be destroyed by fire. For a "destroy_chance_modifier" of 0, the block will never be destroyed by fire, and the fire will burn forever if the "catch_chance_modifier" is greater than 0. The default value of 20 is the same as that of Planks.
	 */
	public function __construct(int $catchChanceModifier = 5, int $destroyChanceModifier = 20) {
		$this->catchChanceModifier = $catchChanceModifier;
		$this->destroyChanceModifier = $destroyChanceModifier;
	}

	public function getName(): string {
		return "minecraft:flammable";
	}

	public function getValue(): CompoundTag {
		return CompoundTag::create()
			->setInt("catch_chance_modifier", $this->catchChanceModifier)
			->setInt("destroy_chance_modifier", $this->destroyChanceModifier);
	}
}