<?php

namespace customiesdevs\customies\block\component;

use customiesdevs\customies\block\Material;
use pocketmine\nbt\tag\CompoundTag;

class MaterialInstancesComponent implements BlockComponent {

	/** @var Material[] */
	private array $materials;

	public function __construct(array $materials) {
		$this->materials = $materials;
	}

	public function getName(): string {
		return "minecraft:material_instances";
	}

	public function getValue(): CompoundTag {
		$materials = CompoundTag::create();
		foreach($this->materials as $material){
			$materials->setTag($material->getTarget(), $material->toNBT());
		}

		return CompoundTag::create()
			->setTag("mappings", CompoundTag::create())
			->setTag("materials", $materials);
	}
}