<?php

namespace customiesdevs\customies\block\component;

use pocketmine\nbt\tag\CompoundTag;

class MaterialInstancesComponent implements BlockComponent {

	public const TARGET_ALL = "*";
	public const TARGET_SIDES = "sides";
	public const TARGET_UP = "up";
	public const TARGET_DOWN = "down";
	public const TARGET_NORTH = "north";
	public const TARGET_EAST = "east";
	public const TARGET_SOUTH = "south";
	public const TARGET_WEST = "west";

	public const RENDER_METHOD_ALPHA_TEST = "alpha_test";
	public const RENDER_METHOD_BLEND = "blend";
	public const RENDER_METHOD_OPAQUE = "opaque";

	private string $target;
	private string $texture;
	private bool $ambientOcclusion;
	private bool $faceDimming;
	private string $renderMethod;

	public function __construct(
		string $target, 
		string $texture, 
		string $renderMethod = self::RENDER_METHOD_OPAQUE, 
		bool $ambientOcclusion = true, 
		bool $faceDimming = true
	) {
		$this->target = $target;
		$this->texture = $texture;
		$this->ambientOcclusion = $ambientOcclusion;
		$this->faceDimming = $faceDimming;
		$this->renderMethod = $renderMethod;
	}

	public function getName(): string {
		return "minecraft:material_instances";
	}

	public function getValue(): CompoundTag {
		return CompoundTag::create()
			->setTag("mappings", CompoundTag::create())
			->setTag("materials", CompoundTag::create()
				->setTag($this->target, CompoundTag::create()
					->setString("texture", $this->texture)
					->setString("render_method", $this->renderMethod)
					->setByte("face_dimming", $this->faceDimming ? 1 : 0)
					->setByte("ambient_occlusion", $this->ambientOcclusion ? 1 : 0)
				)
			);
	}
}