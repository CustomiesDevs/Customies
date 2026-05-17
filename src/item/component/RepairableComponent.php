<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

use customiesdevs\customies\item\properties\RepairItems;

final class RepairableComponent implements ItemComponent {

	/**
	 * Defines the items that can be used to repair a defined item, and the amount of durability each item restores upon repair. Each entry needs to define a list of strings for 'items' that can be used for the repair and an optional 'repair_amount' for how much durability is repaired.
	 * @param RepairItems[] $repairItems List of repair item entries. Each entry needs to define a list of strings for items that can be used for the repair and an optional repair_amount for how much durability is gained.
	 */
	public function __construct(
		private readonly array $repairItems = [],
	) {}

	public function getName(): string {
		return 'minecraft:repairable';
	}

	public function getValue(): array {
		$repairItems = [];
		foreach($this->repairItems as $repairItem) {
			$repairItems[] = $repairItem->toArray();
		}
		return [
			"repair_items" => $repairItems
		];
	}

	public function getPropertyMapping(): ?array {
		return null;
	}
}