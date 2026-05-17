<?php
declare(strict_types=1);

namespace customiesdevs\customies\block\permutations;

use customiesdevs\customies\block\states\BlockStates;

interface BlockPermutations extends BlockStates {

	/**
	 * Adds a permutation to the block.
	 * @param BlockPermutation $permutation
	 */
	public function addPermutation(BlockPermutation $permutation): void;

	/**
	 * Adds multiple permutations at once.
	 * @param BlockPermutation[] $permutations
	 */
	public function addPermutations(array $permutations): void;

	/**
	 * Returns all registered block permutations.
	 * @return BlockPermutation[]
	 */
	public function getPermutations(): array;
}