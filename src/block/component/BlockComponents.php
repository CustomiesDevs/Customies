<?php
declare(strict_types=1);

namespace customiesdevs\customies\block\component;

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
	 * Returns the component with the provided name, or null if it does not exist.
	 * @param string $name
	 * @return BlockComponent|null
	 */
	public function getComponent(string $name): ?BlockComponent;

	/**
	 * @return BlockComponent[]
	 */
	public function getComponents(): array;
}