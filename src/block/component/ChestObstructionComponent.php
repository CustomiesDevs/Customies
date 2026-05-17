<?php

namespace customiesdevs\customies\block\component;

final class ChestObstructionComponent implements BlockComponent {

	/** Will use the Blocks AABB shape to determine if the chest is obstructed from opening when directly above it. */
	public const OBSTRUCTION_SHAPE = "shape";
	/** Will always obstruct chest from opening when directly above it. */
	public const OBSTRUCTION_ALWAYS = "always";
	/** Will never obstruct a chest from opening when directly above it. */
	public const OBSTRUCTION_NEVER = "never";

	/** @var string The rule for determining chest obstruction when a block is directly above it. */
	private string $obstructionRule;

	/**
	 * The rule for determining chest obstruction when a block is directly above it. Defaults to {@see self::OBSTRUCTION_SHAPE}.
	 * @param string $obstructionRule The rule for determining chest obstruction when a block is directly above it. Must be one of:
	 * - {@see self::OBSTRUCTION_SHAPE}
	 * - {@see self::OBSTRUCTION_ALWAYS}
	 * - {@see self::OBSTRUCTION_NEVER}
	 */
	public function __construct(string $obstructionRule = self::OBSTRUCTION_SHAPE) {
		$this->obstructionRule = $obstructionRule;
	}

	public function getName(): string {
		return 'minecraft:chest_obstruction';
	}

	public function getValue(): array {
		return [
			"obstruction_rule" => $this->obstructionRule
		];
	}
}