<?php

namespace customiesdevs\customies\block\component;

final class FlowerPottableComponent implements BlockComponent {

	public function __construct() {}

	public function getName(): string {
		return 'minecraft:flower_pottable';
	}

	public function getValue(): array {
		return [
			"usePreR26U2FlowerPotOffset" => false
		];
	}
}