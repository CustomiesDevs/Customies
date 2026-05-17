<?php
declare(strict_types=1);

namespace customiesdevs\customies\block\component;

interface BlockComponent {

	/**
	 * The component identifier, e.g. "minecraft:collision_box"
	 * @return string
	 */
	public function getName(): string;

	/**
	 * The value of this component, as it would appear in a block JSON.
	 * @return mixed
	 */
	public function getValue(): mixed;
}