<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

use customiesdevs\customies\item\enum\Slot;

final class WearableComponent implements ItemComponent {

	private string $slot;

	public function __construct(Slot $slot) {
		$this->slot = $slot->value;
	}

	public function getName(): string {
		return "minecraft:wearable";
	}

	public function getValue(): array {
		return [
			"slot" => $this->slot
		];
	}

	public function isProperty(): bool {
		return false;
	}
}