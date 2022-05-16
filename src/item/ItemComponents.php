<?php
declare(strict_types=1);

namespace twistedasylummc\customies\item;

use pocketmine\nbt\tag\CompoundTag;

interface ItemComponents {

	/**
	 * Returns the fully-structured CompountTag that can be sent to a client in the ItemComponentsPacket.
	 */
	public function getComponents(): CompoundTag;
}