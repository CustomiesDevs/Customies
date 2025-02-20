<?php

namespace customiesdevs\customies\block\component;

use pocketmine\nbt\tag\CompoundTag;

class DisplayNameComponent implements BlockComponent {

	private string $displayName;

	/**
	 * Specifies the language file key that maps to what text will be displayed when you hover over the block in your inventory and hotbar. 
	 * If the string given can not be resolved as a loc string, the raw string given will be displayed. 
	 * If this component is omitted, the name of the block will be used as the display name.
	 * @param string $displayName Example: `"customBlock"`
	 */
	public function __construct(string $displayName) {
		$this->displayName = $displayName;
	}

	public function getName(): string {
		return "minecraft:display_name";
	}

	public function getValue(): CompoundTag {
		return CompoundTag::create()
			->setString("value", $this->displayName);
	}
}