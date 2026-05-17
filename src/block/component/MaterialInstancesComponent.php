<?php

namespace customiesdevs\customies\block\component;

use customiesdevs\customies\block\properties\Material;

final class MaterialInstancesComponent implements BlockComponent {

	/**
	 * The material instances for a block. Maps face or material_instance names in a geometry file to an actual material instance. You can assign a material instance object to any of these faces: "up", "down", "north", "south", "east", "west", or "*". You can also give an instance the name of your choosing such as "my_instance", and then assign it to a face by doing "north":"my_instance".
	 * @param Material[] $materials
	 */
	public function __construct(private readonly array $materials = []) {
		Material::validMaterials($materials);
	}

	public function getName(): string {
		return 'minecraft:material_instances';
	}

	public function getValue(): array {
		$materials = [];
		foreach($this->materials as $material){
			$materials[$material->getTarget()] = $material->toArray();
		}
		return [
			"mappings" => [],
			"materials" => $materials
		];
	}
}