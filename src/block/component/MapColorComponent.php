<?php

namespace customiesdevs\customies\block\component;

final class MapColorComponent implements BlockComponent {

	private string|array $color;

	/**
	 * Sets the color of the block when rendered to a map. If this component is omitted, the block will not show up on the map.
	 * @param string|array $color The color is represented as a hex value in the format "#RRGGBB". May also be expressed as an array of [R, G, B] from 0 to 255.
	 */
	public function __construct(string|array $color) {
		$this->color = $color;
	}

	public function getName(): string {
		return 'minecraft:map_color';
	}

	public function getValue(): array {
		return [
			"color" => $this->color
		];
	}
}