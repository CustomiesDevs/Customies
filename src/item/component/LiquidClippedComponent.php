<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class LiquidClippedComponent implements ItemComponent {

	private bool $liquidClipped;

	public function __construct(bool $liquidClipped) {
		$this->liquidClipped = $liquidClipped;
	}

	public function getName(): string {
		return "minecraft:liquid_clipped";
	}

	public function getValue(): array {
		return ["value" => $this->liquidClipped];
	}

	public function isProperty(): bool {
		return false;
	}
}
