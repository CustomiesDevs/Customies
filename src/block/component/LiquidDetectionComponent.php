<?php

namespace customiesdevs\customies\block\component;

use pocketmine\nbt\tag\ByteTag;

final class LiquidDetectionComponent implements BlockComponent {

	/** The identifier for water liquid type. */
	public const LIQUID_WATER = "water";

	/** The block stops liquid flow (default behavior). */
	public const BLOCKING = "blocking";
	/** The block is destroyed completely when touched by liquid. */
	public const BROKEN = "broken";
	/** The block is destroyed and drops its item form. */
	public const POPPED = "popped";
	/** The block does not react; liquid visually flows through it. */
	public const NO_REACTION = "no_reaction";

	/** @var string The liquid type this rule applies to (currently only "water"). */
	private string $liquidType;
	/** @var bool Whether the block can contain the liquid (e.g. waterlogged). */
	private bool $canContainLiquid;
	/** @var string Reaction when liquid touches the block. */
	private string $onLiquidTouches;
	/**
	 * @var string[]
	 * Directions from which liquid flow is blocked.
	 * Valid values: "up", "down", "north", "south", "east", "west"
	 */
	private array $stopsLiquidFlowingFromDirection = [];
	/** @var bool Whether the block uses liquid clipping. */
	private bool $liquidClipping = false;

	/**
	 * The liquid type this rule applies to. Defaults to `"water"`.
	 * @param bool $canContainLiquid
	 * Creates a new liquid detection rule.
	 * @param string $liquidType
	 * Whether the block can contain the liquid (e.g. waterlogging).
	 * @param string $onLiquidTouches
	 * How the block reacts when liquid touches it.
	 * Must be one of:
	 * - {@see self::BLOCKING}
	 * - {@see self::BROKEN}
	 * - {@see self::POPPED}
	 * - {@see self::NO_REACTION}
	 * @param string[] $stopsLiquidFlowingFromDirection
	 * Directions in which liquid is prevented from flowing.
	 * If empty, liquid can flow freely in all directions.
	 */
	public function __construct(
		bool $canContainLiquid = false,
		string $liquidType = self::LIQUID_WATER,
		string $onLiquidTouches = self::BLOCKING,
		array $stopsLiquidFlowingFromDirection = [],
		bool $liquidClipping = false
	) {
		$this->canContainLiquid = $canContainLiquid;
		$this->liquidType = $liquidType;
		$this->onLiquidTouches = $onLiquidTouches;
		$this->stopsLiquidFlowingFromDirection = $stopsLiquidFlowingFromDirection;
		$this->liquidClipping = $liquidClipping;
	}

	public function getName(): string {
		return 'minecraft:liquid_detection';
	}

	public function getValue(): array {
		return [
			"detectionRules" => [
				[
					"canContainLiquid" => $this->canContainLiquid,
					"liquidType" => $this->liquidType,
					"onLiquidTouches" => $this->onLiquidTouches,
					"stopsLiquidFromDirection" => new ByteTag($this->directionToMask($this->stopsLiquidFlowingFromDirection)),
					"use_liquid_clipping" => $this->liquidClipping
				]
			]
		];
	}

	private function directionToMask(array $directions): int {
		$mask = 0;
		$map = [
			"down" => 1,
			"up" => 2,
			"north" => 4,
			"south" => 8,
			"west" => 16,
			"east" => 32
		];
		foreach($directions as $dir){
			if(isset($map[$dir])){
				$mask |= $map[$dir];
			}
		}
		return $mask;
	}
}