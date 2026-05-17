<?php

namespace customiesdevs\customies\block\component;

use customiesdevs\customies\block\properties\Material;

final class EmbeddedVisualComponent implements BlockComponent {

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
		return 'minecraft:embedded_visual';
	}

	public function getValue(): array {
		$materials = [];
		foreach($this->materials as $material){
			$materials[$material->getTarget()] = [
				"alpha_masked_tint" => false,
				"face_dimming" => true,
				"isotropic" => false,
				...$material->toArray()
			];
		}
		return [
			"geometry" => $this->geometry->getValue(),
			"material_instances" => $materials
		];
	}
}