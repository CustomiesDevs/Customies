<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\properties;

final class RepairItems {

	/**
	 * @param string[] $items Items usable for repair
	 * @param RepairAmount $repairAmount Repair amount definition
	 */
	public function __construct(
		private readonly array $items,
		private readonly RepairAmount $repairAmount
	) {
		if($items === []){
			throw new \InvalidArgumentException("RepairItems must contain at least one item");
		}
	}

	/**
	 * @return array{
	 *   items: array<int, array{name: string}>,
	 *   repair_amount: int|float|array
	 * }
	 */
	public function toArray(): array {
		return [
			"items" => array_map(
				static fn(string $item) => ["name" => $item],
				$this->items
			),
			"repair_amount" => $this->repairAmount->toArray()
		];
	}

	/** @return string[] */
	public function getItems(): array {
		return $this->items;
	}

	public function getRepairAmount(): RepairAmount {
		return $this->repairAmount;
	}
}