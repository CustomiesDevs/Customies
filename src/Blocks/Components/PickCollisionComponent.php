<?php

namespace customiesdevs\customies\Blocks\Components;

use customiesdevs\customies\Blocks\Components\BlockComponent;use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\ListTag;

class PickCollisionComponent implements BlockComponent {

	private array $origin;
    private array $size;

	/**
	 * Specifies the language file key that maps to what text will be displayed when you hover over the block in your inventory and hotbar.  
	 * If the string given can not be resolved as a loc string, the raw string given will be displayed.  
	 * If this component is omitted, the name of the block will be used as the display name.  
	 * @param array $origin Example using String: `"Custom Block"`
     * @param array $size Example using String: `"Custom Block"`
	 * Example using Localization String: `"block.customies:custom_block.name"`
	 */
	public function __construct(array $origin, array $size) {
		$this->origin = $origin;
        $this->size = $size;
	}

	public function getName(): string {
		return "minecraft:pick_collision";
	}

	public function getValue(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("origin", new ListTag([
                new FloatTag($this->origin[0]),
                new FloatTag($this->origin[1]),
                new FloatTag($this->origin[2])
            ]))
            ->setTag("size", new ListTag([
                new FloatTag($this->size[0]),
                new FloatTag($this->size[1]),
                new FloatTag($this->size[2])
            ]));
	}
}