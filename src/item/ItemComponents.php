<?php
declare(strict_types=1);

namespace customiesdevs\customies\item;

use customiesdevs\customies\item\component\ItemComponent;

interface ItemComponents {

	/**
	 * Adds a component to the item
	 * 
	 * @param ItemComponent $component
	 * @return void
	 */
	public function addComponent(ItemComponent $component): void;

	/**
	 * Returns if the item has the component with the provided name.
	 * 
	 * @param string $name
	 * @return bool
	 */
	public function hasComponent(string $name): bool;

	/**
	 * Returns the component with the provided name, or null if it does not exist.
	 * 
	 * @param string $name
	 * @return ItemComponent|null
	 */
	public function getComponent(string $name): ?ItemComponent;

	/**
	 * Returns all components of the item.
	 *
	 * @return ItemComponent[] Array of all components
	 */
	public function getComponents(): array;
}
