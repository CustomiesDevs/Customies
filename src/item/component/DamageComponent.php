<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class DamageComponent implements ItemComponent {

	private int $damage;

	/**
	 * Determines how much extra damage an item does on attack. Note that this must be a positive value.
	 * @param int $damage Should be a Intger above `0`
	 */
	public function __construct(int $damage) {
		$this->damage = $damage;
	}

	public function getName(): string {
		return "damage";
	}

	public function getValue(): int {
		return $this->damage;
	}

	public function isProperty(): bool {
		return true;
	}
}