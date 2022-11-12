<?php
declare(strict_types=1);

namespace customiesdevs\customies\item;

use customiesdevs\customies\item\component\ItemComponent;
use pocketmine\nbt\tag\CompoundTag;

interface ItemComponents {

	/**
	 * Add component adds a component to the item that can be returned in the getComponents() method to be sent over the networkj.
	 */
	public function addComponent(ItemComponent $component): void;

	/**
	 * Returns if the item has the component with the provided name.
	 */
	public function hasComponent(string $name): bool;

	/**
	 * Returns the fully-structured CompoundTag that can be sent to a client in the ItemComponentsPacket.
	 */
	public function getComponents(): CompoundTag;
}
