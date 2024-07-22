<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class AllowOffHandComponent implements ItemComponent {

	private bool $offHand;

	public function __construct(bool $offHand = true) {
		$this->offHand = $offHand;
	}

	public function getName(): string {
		return "minecraft:allow_off_hand";
	}

	public function getValue(): array {
		return ["value" => $this->offHand];
	}

	public function isProperty(): bool {
		return false;
	}
}