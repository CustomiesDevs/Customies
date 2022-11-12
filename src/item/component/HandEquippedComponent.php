<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class HandEquippedComponent implements ItemComponent {

	private bool $handEquipped;

	public function __construct(bool $handEquipped) {
		$this->handEquipped = $handEquipped;
	}

	public function getName(): string {
		return "minecraft:hand_equipped";
	}

	public function getValue(): bool {
		return $this->handEquipped;
	}

	public function isProperty(): bool {
		return true;
	}
}