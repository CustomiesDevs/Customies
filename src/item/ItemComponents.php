<?php
declare(strict_types=1);

namespace customiesdevs\customies\item;

use customiesdevs\customies\item\component\ItemComponent;
use pocketmine\nbt\tag\CompoundTag;

interface ItemComponents
{

	/**
	 * Add component adds a component to the item that can be returned in the getComponents() method to be sent over the network.
	 *
	 * @param ItemComponent $component
	 * @return void
	 */
	public function addComponent(ItemComponent $component): void;

	/**
	 * Add components adds multiple components to the item that can be returned in the getComponents() method to be sent over the network.
	 *
	 * @param ItemComponent ...$components
	 * @return void
	 */
	public function addComponents(ItemComponent ...$components): void;

	/**
	 * Has component checks if the item has a component with the given name.
	 */

	/**
	 * Returns if the item has the component with the provided name.
	 *
	 * @param string $name
	 * @return bool
	 */
	public function hasComponent(string $name): bool;

	/**
	 * Returns the fully-structured CompoundTag that can be sent to a client in the ItemComponentsPacket.
	 *
	 * @return CompoundTag
	 */
	public function getComponents(): CompoundTag;
}
