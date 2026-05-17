<?php
declare(strict_types=1);

namespace customiesdevs\customies\block\states;

use pocketmine\data\bedrock\block\convert\BlockStateReader;
use pocketmine\data\bedrock\block\convert\BlockStateWriter;

interface BlockStates {

	/**
	 * Adds a state to the block.
	 * @param BlockState $trait
	 * @return void
	 */
	public function addState(BlockState $trait): void;

	/**
	 * Checks if the block has a state by name.
	 * @param string $name
	 * @return bool
	 */
	public function hasState(string $name): bool;

	/**
	 * Retrieves a state by its name.
	 * @param string $name
	 * @return BlockState|null
	 */
	public function getState(string $name): ?BlockState;

	/**
	 * Returns all registered block states.
	 * @return BlockState[]
	 */
	public function getStates(): array;

	/**
	 * Returns an array of the current block property values in the same order as those in getBlockProperties(). It is
	 * used to convert the current properties in to a meta value that can be stored on disk in the world.
	 * @return mixed[]
	 */
	public function getCurrentStates(): array;

	/**
	 * Serializes the block state to the given BlockStateWriter.
	 */
	public function serializeState(BlockStateWriter $blockStateOut): void;

	/**
	 * Deserializes the block state from the given BlockStateReader.
	 */
	public function deserializeState(BlockStateReader $blockStateIn): void;
}