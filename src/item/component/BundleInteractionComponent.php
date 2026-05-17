<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class BundleInteractionComponent implements ItemComponent {

	private int $numViewableSlots;

	/**
	 * Enables the bundle-specific interaction scheme and tooltip for an item.
	 * To use this component, the item must have a `minecraft:storage_item` item component defined.
	 * @param int $numViewableSlots The maximum number of slots in the bundle viewable by the player. Can be from 1 to 64. Default is 12.
	 * @throws \InvalidArgumentException if the number of viewable slots is not between 1 and 64.
	 */
	public function __construct(int $numViewableSlots = 12) {
		if($numViewableSlots < 1 || $numViewableSlots > 64) {
			throw new \InvalidArgumentException("Number of viewable-slots must be between 1 and 64, $numViewableSlots given");
		}
		$this->numViewableSlots = $numViewableSlots;
	}

	public function getName(): string {
		return 'minecraft:bundle_interaction';
	}

	public function getValue(): array {
		return [
			"num_viewable_slots" => $this->numViewableSlots
		];
	}

	public function getPropertyMapping(): ?array {
		return null;
	}
}