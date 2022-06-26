<?php
declare(strict_types=1);

namespace customiesdevs\customies\item;

use pocketmine\nbt\tag\CompoundTag;

interface ItemComponents {

	/**
	 * Returns the fully-structured CompoundTag that can be sent to a client in the ItemComponentsPacket.
	 */
	public function getComponents(): CompoundTag;
}
