<?php

namespace customiesdevs\customies\block\component;

use customiesdevs\customies\block\properties\Material;
use pocketmine\nbt\tag\ByteTag;

final class ItemVisualComponent implements BlockComponent {

	/**
	 * @param Material[] $materials
	 */
	public function __construct(
		private readonly GeometryComponent $geometry, 
		private readonly array $materials = []
	) {
		Material::validMaterials($materials);
	}

	public function getName(): string {
		return 'minecraft:item_visual';
	}

	public function getValue(): array {
		$materials = [];
		foreach($this->materials as $material){
			$materials[$material->getTarget()] = [
				...$material->toArray(),
				"packed_bools" => new ByteTag(Material::FACE_DIMMING)
			];
		}
		return [
			"geometryDescription" => $this->geometry->getValue(),
			"materialInstancesDescription" => [
				"mappings" => [],
				"materials" => $materials
			]
		];
	}
}