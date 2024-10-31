<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class AttackDamageComponent implements ItemComponent {

	private int $damage;

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