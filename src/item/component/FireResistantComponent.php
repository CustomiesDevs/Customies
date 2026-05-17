<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class FireResistantComponent implements ItemComponent {

	private bool $fireResistant;

	/**
	 * Determines whether the item is immune to burning when dropped in fire or lava.
	 * @param bool $fireResistant Determines whether the item is immune to burning when dropped in fire or lava. Default value: true.
	 */
	public function __construct(bool $fireResistant = true) {
		$this->fireResistant = $fireResistant;
	}

	public function getName(): string {
		return 'minecraft:fire_resistant';
	}

	public function getValue(): array {
		return [
			"value" => $this->fireResistant
		];
	}

	public function getPropertyMapping(): ?array {
		return null;
	}
}