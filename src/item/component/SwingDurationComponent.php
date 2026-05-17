<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class SwingDurationComponent implements ItemComponent {

	private float $swingDuration;

	/**
	 * Duration, in seconds, of the item's swing animation played when mining or attacking. Affects visuals only and does not impact attack frequency or other gameplay mechanics.
	 * @param float $swingDuration Duration, in seconds, of the item's swing animation played when mining or attacking. Affects visuals only and does not impact attack frequency or other gameplay mechanics. Default value: 0.3.
	 */
	public function __construct(float $swingDuration = 0.3) {
		if($swingDuration < 0){
			throw new \InvalidArgumentException("Swing duration must be a positive number, $swingDuration given");
		}
		$this->swingDuration = $swingDuration;
	}

	public function getName(): string {
		return 'minecraft:swing_duration';
	}

	public function getValue(): array {
		return [
			"value" => $this->swingDuration
		];
	}

	public function getPropertyMapping(): ?array {
		return null;
	}
}