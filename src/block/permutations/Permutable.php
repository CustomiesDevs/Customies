<?php
declare(strict_types=1);

namespace customiesdevs\customies\block\permutations;

interface Permutable {

	/**
	 * Returns an array of the different block properties the block has. When the block is registered, it is registered
	 * with all the possible combinations of all the block properties returned.
	 * @return BlockProperty[]
	 */
	public function getBlockProperties(): array;

	/**
	 * Returns an array of the permutations the block has. They contain molang queries that can use the block properties
	 * to control the components based on different states server-side.
	 * @return Permutation[]
	 */
	public function getPermutations(): array;

	/**
	 * Returns an array of the current block property values in the same order as those in getBlockProperties(). It is
	 * used to convert the current properties in to a meta value that can be stored on disk in the world.
	 * @return BlockProperty[]
	 */
	public function getCurrentBlockProperties(): array;
}