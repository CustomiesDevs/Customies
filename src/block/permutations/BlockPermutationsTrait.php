<?php
declare(strict_types=1);

namespace customiesdevs\customies\block\permutations;

use customiesdevs\customies\block\states\BlockStatesTrait;

trait BlockPermutationsTrait {
	use BlockStatesTrait;

	/**
	 * Registered block permutations.
	 * @var BlockPermutation[]
	 */
	private array $blockPermutations;

	/**
	 * Adds a permutation to the block.
	 * @param BlockPermutation $permutation
	 */
	public function addPermutation(BlockPermutation $permutation): void {
		$this->blockPermutations[] = $permutation;
	}

	/**
	 * Adds multiple permutations at once.
	 * @param BlockPermutation[] $permutations
	 */
	public function addPermutations(array $permutations): void {
		foreach($permutations as $permutation) {
			$this->addPermutation($permutation);
		}
	}

	/**
	 * Returns all registered block permutations.
	 * @return BlockPermutation[]
	 */
	public function getPermutations(): array {
		return $this->blockPermutations;
	}
}