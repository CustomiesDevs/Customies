<?php
declare(strict_types=1);

namespace customiesdevs\customies\Items\Components;

use customiesdevs\customies\Items\Components\ItemComponentBase;final class ChargeableComponent implements ItemComponentBase {

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