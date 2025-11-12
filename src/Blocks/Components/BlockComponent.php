<?php
declare(strict_types=1);

namespace customiesdevs\customies\Blocks\Components;

use pocketmine\nbt\tag\CompoundTag;

interface BlockComponent {

	/**
	 * Returns the name of the component
	 * @return string
	 */
	public function getName(): string;

	/**
	 * Returns the value of the component
	 * @return CompoundTag
	 */
	public function getValue(): CompoundTag;
}