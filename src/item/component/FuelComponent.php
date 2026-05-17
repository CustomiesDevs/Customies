<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class FuelComponent implements ItemComponent {

	private float $duration;

	/**
	 * Allows this item to be used as fuel in a furnace to 'cook' other items.
	 * @param float $duration Amount of time, in seconds, this fuel will cook items.
	 * @throws \InvalidArgumentException if the fuel duration is less than 0.05
	 */
	public function __construct(float $duration = 0.05) {
		if($duration < 0.05){
			throw new \InvalidArgumentException("Fuel duration must be at least 0.05 seconds, $duration given");
		}
		$this->duration = $duration;
	}

	public function getName(): string {
		return 'minecraft:fuel';
	}

	public function getValue(): array {
		return [
			"duration" => $this->duration
		];
	}

	public function getPropertyMapping(): ?array {
		return null;
	}
}