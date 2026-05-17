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
	 * @throws \InvalidArgumentException if the min or max damage chance is not between 0 and 100, or if minDamageChance is greater than maxDamageChance, or if maxDurability is not between 0 and 2147483647.
	 */
	public function __construct(int $maxDurability, int $minDamageChance = 100, int $maxDamageChance = 100) {
		self::validate($minDamageChance, 'Minimum');
		self::validate($maxDamageChance, 'Maximum');
		if($minDamageChance > $maxDamageChance){
			throw new \InvalidArgumentException("Minimum damage chance ($minDamageChance) cannot be greater than maximum damage chance ($maxDamageChance)");
		}
		if($maxDurability < 0 || $maxDurability > 2147483647){
			throw new \InvalidArgumentException("Max durability must be between 0 and 2147483647, $maxDurability given");
		}
		$this->maxDurability = $maxDurability;
		$this->minDamageChance = $minDamageChance;
		$this->maxDamageChance = $maxDamageChance;
	}

	public function getName(): string {
		return 'minecraft:durability';
	}

	public function getValue(): array {
		return [
			"damage_chance" => [
				"max" => $this->maxDamageChance,
				"min" => $this->minDamageChance
			],
			"max_durability" => $this->maxDurability
		];
	}

	public function getPropertyMapping(): ?array {
		return null;
	}

	private static function validate(int $value, string $type): void {
		if($value < 0 || $value > 100){
			throw new \InvalidArgumentException("$type damage chance must be between 0 and 100, $value given");
		}
	}
}