<?php

namespace customiesdevs\customies\block\component;

use pocketmine\math\Vector3;

final class SelectionBoxComponent implements BlockComponent {

	private bool $useSelectionBox;
	private Vector3 $origin;
	private Vector3 $size;

	/**
	 * Defines the area of the block that is selected by the player's cursor. If set to true, default values are used. If set to false, this block is not selectable by the player's cursor. If this component is omitted, default values are used.
	 * @param Vector3 $origin MMinimal position of the bounds of the selection box. "origin" is specified as [x, y, z] and must be in the range (-8, 0, -8) to (8, 16, 8), inclusive.
	 * @param Vector3 $size Size of each side of the selection box. Size is specified as [x, y, z]. "origin" + "size" must be in the range (-8, 0, -8) to (8, 16, 8), inclusive.
	 * @param bool $useSelectionBox If selection should be enabled, default is set to `true`.
	 */
	public function __construct(bool $useSelectionBox = true, ?Vector3 $origin = new Vector3(-8.0, 0.0, -8.0), ?Vector3 $size = new Vector3(16.0, 16.0, 16.0)) {
		$this->useSelectionBox = $useSelectionBox;
		$this->origin = $origin;
		$this->size = $size;
	}

	public function getName(): string {
		return 'minecraft:selection_box';
	}

	public function getValue(): array {
		return [
			"enabled" => $this->useSelectionBox,
			"origin" => [
				$this->origin->getX(), 
				$this->origin->getY(), 
				$this->origin->getZ()
			],
			"size" => [
				$this->size->getX(), 
				$this->size->getY(), 
				$this->size->getZ()
			]
		];
	}
}