<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class BundleInteractionComponent implements ItemComponent {

	private int $numViewableSlots;

	/**
	 * `minecraft:bundle_interaction` enables the bundle-specific interaction scheme and tooltip for an item.
	 * To use this component, the item must have a `minecraft:storage_item` item component defined.
	 * @param int $numViewableSlots The number of slots that are viewable in the bundle.
	 */
	public function __construct(int $numViewableSlots) {
		$this->numViewableSlots = $numViewableSlots;
	}

	public function getName(): string {
		return "minecraft:bundle_interaction";
	}

	public function getValue(): array {
		return [
			"num_viewable_slots" => $this->numViewableSlots
		];
	}

	public function isProperty(): bool {
		return false;
	}
}