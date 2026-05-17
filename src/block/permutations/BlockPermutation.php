<?php
declare(strict_types=1);

namespace customiesdevs\customies\block\permutations;

use customiesdevs\customies\block\component\BlockComponent;

class BlockPermutation {

	/**
	 * @param string $condition The condition to evaluate for this permutation
	 * @param BlockComponent $components The components to apply if the condition is met
	 */
	public function __construct(
		private readonly string $condition,
		private readonly BlockComponent $components
	) {}

	/**
	 * Gets the condition string for this permutation.
	 */
	public function getCondition(): string {
		return $this->condition;
	}

	/**
	 * Gets the components associated with this permutation.
	 */
	public function getComponents(): BlockComponent {
		return $this->components;
	}

	/**
	 * Converts the BlockPermutation to an array format.
	 */
	public function toArray(): array {
		return [
			"condition" => $this->condition,
			"components" => [
				$this->components->getName() => $this->components->getValue()
			]
		];
	}
}