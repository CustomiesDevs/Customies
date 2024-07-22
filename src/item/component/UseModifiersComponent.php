<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class UseModifiersComponent implements ItemComponent {

	private int $useDuration;
	private int $movementModifier;

	public function __construct(int $useDuration, int $movementModifier) {
		$this->useDuration = $useDuration;
		$this->movementModifier = $movementModifier;
	}

	public function getName(): string {
		return "minecraft:use_modifiers";
	}

	public function getValue(): array {
		return [
			"use_duration" => $this->useDuration,
			"movement_modifier" => $this->movementModifier
		];
	}

	public function isProperty(): bool {
		return false;
	}
}