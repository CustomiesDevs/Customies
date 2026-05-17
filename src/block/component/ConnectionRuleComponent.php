<?php

namespace customiesdevs\customies\block\component;

final class ConnectionRuleComponent implements BlockComponent {

	/**
	 * Allows any block (fences, walls, panes, etc.) to connect.
	 */
	public const RULE_ACCEPTS_ALL = "all";
	/**
	 * Allows only fences to connect.
	 * Prevents walls and glass panes from connecting.
	 */
	public const RULE_ACCEPTS_ONLY_FENCES = "only_fences";
	/**
	 * Prevents all blocks from connecting.
	 */
	public const RULE_ACCEPTS_NONE = "none";
	private const VALID_DIRECTIONS = [
		"north",
		"south",
		"east",
		"west"
	];
	/** @var string[]*/
	private array $enabledDirections;
	private string $acceptsConnectionsFrom;

	/**
	 * Determines whether other blocks (such as fences, walls, or glass panes)
	 * can visually and physically connect to this block.
	 * 
	 * @param string   $acceptsConnectionsFrom
	 * @param string[] $enabledDirections
	 */
	public function __construct(
		string $acceptsConnectionsFrom = self::RULE_ACCEPTS_ALL,
		array $enabledDirections = self::VALID_DIRECTIONS
	) {
		if(!in_array($acceptsConnectionsFrom, [
			self::RULE_ACCEPTS_ALL,
			self::RULE_ACCEPTS_ONLY_FENCES,
			self::RULE_ACCEPTS_NONE
		], true)){
			throw new \InvalidArgumentException("Invalid connection acceptance rule: {$acceptsConnectionsFrom}");
		}
		foreach($enabledDirections as $direction){
			if(!in_array($direction, self::VALID_DIRECTIONS, true)){
				throw new \InvalidArgumentException("Invalid connection direction: {$direction}");
			}
		}
		$this->acceptsConnectionsFrom = $acceptsConnectionsFrom;
		$this->enabledDirections = array_values($enabledDirections);
	}

	public function getName(): string {
		return 'minecraft:connection_rule';
	}

	public function getValue(): array {
		return [
			"accepts_connections_from" => $this->acceptsConnectionsFrom,
			"enabled_directions" => $this->enabledDirections
		];
	}
}