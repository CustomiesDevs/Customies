<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class ChargeableComponent implements ItemComponent {

	private float $movementModifier;

	public function __construct(float $movementModifier) {
		$this->movementModifier = $movementModifier;
	}

	public function getName(): string {
		return "minecraft:chargeable";
	}

	public function getValue(): array {
		return [
			"movement_modifier" => $this->movementModifier
		];
	}

	public function isProperty(): bool {
		return false;
	}
}