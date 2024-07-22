<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class DurabilityComponent implements ItemComponent {

	private array $damageChance;
	private int $maxDurability;

	public function __construct(array $damageChance, int $maxDurability) {
		$this->damageChance = $damageChance;
		$this->maxDurability = $maxDurability;
	}

	public function getName(): string {
		return "minecraft:durability";
	}

	public function getValue(): array {
		return [
			"damage_chance" => $this->damageChance,
			"max_durability" => $this->maxDurability
		];
	}

	public function isProperty(): bool {
		return false;
	}
}