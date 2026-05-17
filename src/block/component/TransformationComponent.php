<?php

namespace customiesdevs\customies\block\component;

use pocketmine\math\Vector3;

final class TransformationComponent implements BlockComponent {

	/**
	 * The block's translation, rotation and scale with respect to the center of its world position.
	 * @param Vector3 $rotation The block's rotation in increments of 90 degrees
	 * @param Vector3 $rotationPivot The point to apply rotation around	
	 * @param Vector3 $scale The block's scale
	 * @param Vector3 $scalePivot The point to apply scale around
	 * @param Vector3 $translation The block's translation
	 */
	public function __construct(
		private readonly Vector3 $rotation = new Vector3(0.0, 0.0, 0.0),
		private readonly Vector3 $rotationPivot = new Vector3(0.0, 0.0, 0.0),
		private readonly Vector3 $scale = new Vector3(1.0, 1.0, 1.0),
		private readonly Vector3 $scalePivot = new Vector3(0.0, 0.0, 0.0),
		private readonly Vector3 $translation = new Vector3(0.0, 0.0, 0.0)
	) {}

	public function getName(): string {
		return 'minecraft:transformation';
	}

	public function getValue(): array {
		return [
			"RX" => (int) self::rotationToIndex($this->rotation->x),
			"RY" => (int) self::rotationToIndex($this->rotation->y),
			"RZ" => (int) self::rotationToIndex($this->rotation->z),
			"RXP" => (float) $this->rotationPivot->x,
			"RYP" => (float) $this->rotationPivot->y,
			"RZP" => (float) $this->rotationPivot->z,
			"SX" => (float) $this->scale->x,
			"SY" => (float) $this->scale->y,
			"SZ" => (float) $this->scale->z,
			"SXP" => (float) $this->scalePivot->x,
			"SYP" => (float) $this->scalePivot->y,
			"SZP" => (float) $this->scalePivot->z,
			"TX" => (float) $this->translation->x,
			"TY" => (float) $this->translation->y,
			"TZ" => (float) $this->translation->z,
			"hasJsonVersionBeforeValidation" => false
		];
	}

	private static function rotationToIndex(float $d): int {
		$d = ((int) $d) % 360;
		if($d < 0){
			$d += 360;
		}
		return match($d){
			0 => 0, // North
			90 => 1, // West
			180 => 2, // South
			270, -90 => 3, // East
			default => 0 // North By Default
		};
	}
}