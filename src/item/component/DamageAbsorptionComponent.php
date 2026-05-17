<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

use customiesdevs\customies\item\properties\DamageCause;

final class DamageAbsorptionComponent implements ItemComponent {

	 /**
	 * List of damage causes that can be absorbed by the item.
	 * @var DamageCause[]
	 */
	private array $absorbableCauses = [];

	/**
	 * It allows an item to absorb damage that would otherwise be dealt to its wearer.
	 * For this to happen, the item needs to be equipped in an armor slot.
	 * The absorbed damage reduces the item's durability, with any excess damage being ignored.
	 * Because of this, the item also needs a `minecraft:durability` component.
	 * @param array $absorbableCauses List of damage causes that can be absorbed by the item. By default, no damage cause is absorbed.
	 */
	public function __construct(array $absorbableCauses = []) {
		foreach($absorbableCauses as $cause){
			$this->addCause($cause);
		}
	}

	public function getName(): string {
		return 'minecraft:damage_absorption';
	}

	public function getValue(): array {
		return [
			'absorbable_causes' => array_map(
				static fn (DamageCause $cause) => $cause->value,
				$this->absorbableCauses
			)
		];
	}

	public function getPropertyMapping(): ?array {
		return null;
	}

	/**
	 * Adds a damage cause to the absorbable list.
	 * @param DamageCause|string $cause
	 */
	public function addCause(DamageCause|string $cause): self {
		if(is_string($cause)){
			$cause = DamageCause::tryFrom($cause);
			if($cause === null){
				throw new \InvalidArgumentException("Invalid damage cause: {$cause}");
			}
		}
		if(!in_array($cause, $this->absorbableCauses, true)){
			$this->absorbableCauses[] = $cause;
		}
		return $this;
	}
}