<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class ShouldDespawnComponent implements ItemComponent {

	private bool $shouldDespawn;

	public function __construct(bool $shouldDespawn) {
		$this->shouldDespawn = $shouldDespawn;
	}

	public function getName(): string {
		return "minecraft:should_despawn";
	}

	public function getValue(): array {
		return ["value" => $this->shouldDespawn];
	}

	public function isProperty(): bool {
		return false;
	}
}