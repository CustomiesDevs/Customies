<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class DurabilityComponent implements ItemComponent {

	private int $maxDurability;
	private int $minDamageChance;
	private int $maxDamageChance;

	/**
	 * Sets how much damage the item can take before breaking, and allows the item to be combined at an anvil, grindstone, or crafting table.
	 * @param int $maxDurability Max durability is the amount of damage that this item can take before breaking
	 * @param int $minDamageChance Specifies the percentage minimum chance for durability to take damage. Range: [0, 100]. Default is set to `100`
	 * @param int $maxDamageChance Specifies the percentage maximum chance for durability to take damage. Range: [0, 100]. Default is set to `100`
	 */
	public function __construct(int $maxDurability, int $minDamageChance = 100, int $maxDamageChance = 100) {
		$this->maxDurability = $maxDurability;
		$this->minDamageChance = $minDamageChance;
		$this->maxDamageChance = $maxDamageChance;
	}

	public function getName(): string {
		return "minecraft:durability";
	}

	public function getValue(): array {
		return [
			"damage_chance" => [
				"min" => $this->minDamageChance,
				"max" => $this->maxDamageChance
			],
			"max_durability" => $this->maxDurability
		];
	}

	public function isProperty(): bool {
		return false;
	}
}