<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class ShouldDespawnComponent implements ItemComponent {

	private bool $shouldDespawn;

	/**
	 * Determines if an item should despawn while floating in the world.
	 * @param bool $shouldDespawn item should eventually despawn while floating in the world, Default is set to `true`
	 */
	public function __construct(bool $shouldDespawn = true) {
		$this->shouldDespawn = $shouldDespawn;
	}

	public function getName(): string {
		return "should_despawn";
	}

	public function getValue(): bool {
		return $this->shouldDespawn;
	}

	public function isProperty(): bool {
		return true;
	}
}