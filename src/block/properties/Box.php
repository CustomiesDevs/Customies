<?php
declare(strict_types=1);

namespace customiesdevs\customies\block\properties;

use pocketmine\math\AxisAlignedBB;
use pocketmine\math\Vector3;

/**
 * Represents a collision box definition used by the `minecraft:collision_box`
 * (and similar) block components.
 *
 * Coordinates are defined in **block-relative space**, where the block center
 * is `(0, 0, 0)` and the full block spans from `(-8, 0, -8)` to `(8, 24, 8)`.
 *
 * ### Constraints
 * - **Origin** must be in range `(-8, 0, -8)` to `(7, 23, 7)`
 * - **Size** must be in range `(1, 1, 1)` to `(16, 24, 16)`
 * - **Origin + Size** must not exceed `(8, 24, 8)`
 *
 * All values are automatically clamped to valid ranges.
 */
final class Box {

	/** @var Vector3 Origin (minimum corner) of the box in block-relative coordinates. */
	private Vector3 $origin;
	/** @var Vector3 Size (width, height, depth) of the box. */
	private Vector3 $size;

	/**
	 * Creates a new collision box definition.
	 * Values are clamped to Minecraft's valid block collision bounds.
	 * @param Vector3 $origin Minimum corner of the box.
	 * @param Vector3 $size Dimensions of the box.
	 */
	public function __construct(Vector3 $origin, Vector3 $size) {
		// Clamp origin
		$originX = max(-8, min(7, $origin->x));
		$originY = max(0, min(23, $origin->y));
		$originZ = max(-8, min(7, $origin->z));
		
		// Clamp size
		$sizeX = max(1, min(16, $size->x));
		$sizeY = max(1, min(24, $size->y));
		$sizeZ = max(1, min(16, $size->z));
		
		// Clamp to ensure origin + size is valid
		$sizeX = min($sizeX, 8 - $originX);
		$sizeY = min($sizeY, 24 - $originY);
		$sizeZ = min($sizeZ, 8 - $originZ);
		
		$this->origin = new Vector3($originX, $originY, $originZ);
		$this->size = new Vector3($sizeX, $sizeY, $sizeZ);
	}

	/**
	 * Creates a box from an {@see AxisAlignedBB}.
	 * @param AxisAlignedBB $bb The bounding box to convert.
	 * @return self
	 */
	public static function fromAABB(AxisAlignedBB $bb): self {
		return new self(
			new Vector3($bb->minX, $bb->minY, $bb->minZ),
			new Vector3($bb->maxX - $bb->minX, $bb->maxY - $bb->minY, $bb->maxZ - $bb->minZ)
		);
	}

	/**
	 * Returns the origin (minimum corner) of the box.
	 * @return Vector3
	 */
	public function getOrigin(): Vector3 { return $this->origin; }

	/**
	 * Returns the size of the box.
	 * @return Vector3
	 */
	public function getSize(): Vector3 { return $this->size; }

	/**
	 * Returns the maximum corner of the box (origin + size).
	 * @return Vector3
	 */
	public function getMax(): Vector3 { return $this->origin->addVector($this->size); }

	/**
	 * Converts the box into the Bedrock NBT array format.
	 *
	 * Coordinates are converted from block-relative space into
	 * client-expected values (X and Z shifted by +8).
	 * @return array{
	 *     minX: float,
	 *     minY: float,
	 *     minZ: float,
	 *     maxX: float,
	 *     maxY: float,
	 *     maxZ: float
	 * }
	 */
	public function toNbtArray(): array {
		$max = $this->getMax();
		return [
			"minX" => (float) ($this->origin->x + 8),
			"minY" => (float) $this->origin->y,
			"minZ" => (float) ($this->origin->z + 8),
			"maxX" => (float) ($max->x + 8),
			"maxY" => (float) $max->y,
			"maxZ" => (float) ($max->z + 8),
		];
	}

	/**
	 * Converts this box into an {@see AxisAlignedBB}.
	 * @return AxisAlignedBB
	 */
	public function toAxisAlignedBB(): AxisAlignedBB {
		$max = $this->getMax();
		return new AxisAlignedBB(
			$this->origin->x, $this->origin->y, $this->origin->z,
			$max->x, $max->y, $max->z
		);
	}

	/**
	 * Returns a default full block collision box.
	 * @return Box
	 */
	public static function defaultBox(): Box {
		return new self(new Vector3(-8.0, 0.0, -8.0), new Vector3(16.0, 16.0, 16.0));
	}
}