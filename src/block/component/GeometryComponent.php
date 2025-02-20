<?php

namespace customiesdevs\customies\block\component;

use pocketmine\nbt\tag\CompoundTag;

class GeometryComponent implements BlockComponent {

	private string $geometry;
	private CompoundTag $boneVisibility;

	/**
	 * The description identifier of the geometry to use to render this block. This identifier must either match an existing geometry identifier in any of the loaded resource packs or be one of the currently supported Vanilla identifiers: "minecraft:geometry.full_block" or "minecraft:geometry.cross".
	 * @param string $geometry
	 */
	public function __construct(string $geometry = "minecraft:geometry.full_block") {
		$this->geometry = $geometry;
		$this->boneVisibility = CompoundTag::create();
	}

	public function getName(): string {
		return "minecraft:geometry";
	}

	public function getValue(): CompoundTag {		
		return CompoundTag::create()
			->setTag("bone_visibility", $this->boneVisibility)
			->setString("culling", "")
			->setString("identifier", $this->geometry);
	}

	public function addBoneVisibility(string $boneName, bool|string $visibility): self {
		if(is_string($visibility) && !is_bool($visibility)){
			$this->boneVisibility->setTag($boneName, CompoundTag::create()
				->setString("expression", $visibility)
				->setInt("version", 12));
		} elseif(is_bool($visibility)){
			if($visibility){
				$this->boneVisibility->setFloat($boneName, 1);
			} else {
				$this->boneVisibility->setFloat($boneName, 0);
			}
		}
		return $this;
	}
}