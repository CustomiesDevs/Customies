<?php

namespace customiesdevs\customies\block;

use customiesdevs\customies\block\component\BlockComponent;

interface BlockComponents {

	/**
	 * Add component adds a component to the block that can be returned in the getComponents() method to be sent over
	 * the network.
	 * @param BlockComponent $component
	 * @return void
	 */
	public function addComponent(BlockComponent $component): void;

	/**
	 * Returns if the block has the component with the provided name.
	 * @param string $name
	 * @return bool
	 */
	public function hasComponent(string $name): bool;

	/**
	 * @return BlockComponent[]
	 */
	public function getComponents(): array;
}