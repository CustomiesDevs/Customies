<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class DamageAbsorptionComponent implements ItemComponent {

	private array $absorbableCauses;

	/**
	 * Causes the item to absorb damage that would otherwise be dealt to its wearer.
	 * For this to happen, the item needs to have the durability component and be equipped in an armor slot.
	 * @param array $absorbableCauses List of damage causes (such as entity_attack and magma) that can be absorbed by the item.
	 */
	public function __construct(array $absorbableCauses) {
		$this->absorbableCauses = $absorbableCauses;
	}

	public function getName(): string {
		return "minecraft:damage_absorption";
	}

	public function getValue(): array {
		return [
			"absorbable_causes" => $this->absorbableCauses
		];
	}

	public function isProperty(): bool {
		return false;
	}
}