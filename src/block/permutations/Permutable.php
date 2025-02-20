<?php
declare(strict_types=1);

namespace customiesdevs\customies\block\permutations;

use pocketmine\data\bedrock\block\convert\BlockStateReader;
use pocketmine\data\bedrock\block\convert\BlockStateWriter;

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
	 * Serializes the block state to the given BlockStateWriter.
	 */
	public function serializeState(BlockStateWriter $blockStateOut): void;

	/**
	 * Deserializes the block state from the given BlockStateReader.
	 */
	public function deserializeState(BlockStateReader $blockStateIn): void;
}
