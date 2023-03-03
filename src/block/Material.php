<?php
declare(strict_types=1);

namespace customiesdevs\customies\block;

use pocketmine\nbt\tag\CompoundTag;

use customiesdevs\customies\block\enum\{Target, RenderMethod};

final class Material
{


	public function __construct(private readonly Target $target, private readonly string $texture, private readonly RenderMethod $renderMethod,
								private readonly bool $faceDimming = true, private readonly bool $ambientOcclusion = true) {}

	/**
	 * Returns the targeted face for the material.
	 *
	 * @return Target
	 */
	public function getTarget(): Target
	{
		return $this->target;
	}

	/**
	 * Returns the material in the correct NBT format supported by the client.
	 *
	 * @return CompoundTag
	 */
	public function toNBT(): CompoundTag
	{
		return CompoundTag::create()
			->setString("texture", $this->texture)
			->setString("render_method", $this->renderMethod->value)
			->setByte("face_dimming", $this->faceDimming ? 1 : 0)
			->setByte("ambient_occlusion", $this->ambientOcclusion ? 1 : 0);
	}
}
