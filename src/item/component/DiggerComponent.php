<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

use pocketmine\block\Block;
use pocketmine\world\format\io\GlobalBlockStateHandlers;
use function array_map;
use function implode;

final class DiggerComponent implements ItemComponent {

	private array $destroySpeeds;
	private bool $useEfficiency;

	/**
	 * Allows a creator to determine how quickly an item can dig specific blocks.
	 * @param bool $useEfficiency Determines whether the item should be impacted if the `efficiency` enchant is applied to it.
	 */
	public function __construct(bool $useEfficiency) {
		$this->useEfficiency = $useEfficiency;
	}

	public function getName(): string {
		return "minecraft:digger";
	}

	public function getValue(): array {
		return [
			"use_efficiency" => $this->useEfficiency,
			"destroy_speeds" => $this->destroySpeeds
		];
	}

	public function isProperty(): bool {
		return false;
	}

	/**
	 * Add blocks to the `destroy_speeds` array in the required format.
	 * @param int $speed Digging speed for the correlating block(s)
	 * @param Block ...$blocks A list of blocks to dig with correlating speeds of digging
	 */
	public function withBlocks(int $speed, Block ...$blocks): DiggerComponent {
		foreach($blocks as $block){
			$this->destroySpeeds[] = [
				"block" => [
					"name" => GlobalBlockStateHandlers::getSerializer()->serialize($block->getStateId())->getName()
				],
				"speed" => $speed
			];
		}
		return $this;
	}

	/**
	 * Add blocks to the `destroy_speeds` array in the required format.
	 * @param int $speed Digging speed for the correlating block(s)
	 * @param string ...$tags A list of blocks to dig with correlating speeds of digging
	 */
	public function withTags(int $speed, string ...$tags): DiggerComponent {
		$query = implode(",", array_map(fn($tag) => "'" . $tag . "'", $tags));
		$this->destroySpeeds[] = [
			"block" => [
				"tags" => "query.any_tag(" . $query . ")"
			],
			"speed" => $speed
		];
		return $this;
	}
}