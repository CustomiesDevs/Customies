<?php

namespace customiesdevs\customies\block\component;

use customiesdevs\customies\block\properties\PlacementCondition;
use InvalidArgumentException;

final class PlacementFilterComponent implements BlockComponent {

	/** @var PlacementCondition[] */
	private array $conditions = [];

	/**
	 * @param PlacementCondition[] $conditions (max 64)
	 */
	public function __construct(array $conditions = []) {
		if(count($conditions) > 64){
			throw new InvalidArgumentException("Placement filter may not exceed 64 conditions");
		}
		$this->conditions = $conditions;
	}

	public function getName(): string {
		return 'minecraft:placement_filter';
	}

	public function getValue(): array {
		return [
			"conditions" => array_map(
				static fn(PlacementCondition $c) => $c->toArray(),
				$this->conditions
			)
		];
	}

	public function addCondition(PlacementCondition $condition): self {
		if(count($this->conditions) >= 64){
			throw new InvalidArgumentException("Placement filter may not exceed 64 conditions");
		}
		$this->conditions[] = $condition;
		return $this;
	}
}