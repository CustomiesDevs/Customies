<?php
declare(strict_types=1);

namespace customiesdevs\customies\block;

use pocketmine\nbt\tag\CompoundTag;

final class Material {

	public const TARGET_ALL = "*";
	public const TARGET_UP = "up";
	public const TARGET_DOWN = "down";
	public const TARGET_SIDES = "sides";

	public const RENDER_METHOD_ALPHA_TEST = "alpha_test";
	public const RENDER_METHOD_BLEND = "blend";
	public const RENDER_METHOD_OPAQUE = "opaque";

	private string $target;
	private string $texture;
	private string $renderMethod;
	private bool $faceDimming;
	private bool $ambientOcclusion;

	public function __construct(string $target, string $texture, string $renderMethod, bool $faceDimming = true, bool $ambientOcclusion = true) {
		$this->target = $target;
		$this->texture = $texture;
		$this->renderMethod = $renderMethod;
		$this->faceDimming = $faceDimming;
		$this->ambientOcclusion = $ambientOcclusion;
	}

	/**
	 * Returns the targeted face for the material.
	 */
	public function getTarget(): string {
		return $this->target;
	}

	/**
	 * Returns the material in the correct NBT format supported by the client.
	 */
	public function toNBT(): CompoundTag {
		return CompoundTag::create()
			->setString("texture", $this->texture)
			->setString("render_method", $this->renderMethod)
			->setByte("face_dimming", $this->faceDimming ? 1 : 0)
			->setByte("ambient_occlusion", $this->ambientOcclusion ? 1 : 0);
	}
}
