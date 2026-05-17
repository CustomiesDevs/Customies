<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

use customiesdevs\customies\block\permutations\BlockPermutations;
use customiesdevs\customies\block\states\BlockState;
use pocketmine\block\Block;
use pocketmine\world\format\io\GlobalBlockStateHandlers;
use function array_map;
use function implode;

final class DiggerComponent implements ItemComponent {

	/**
	 * @var array<int, array{
	 *     block: array{
	 *         name?: string,
	 *         states?: array<string, mixed>,
	 *         tags?: string
	 *     },
	 *     speed: int
	 * }>
	 */
	private array $destroySpeeds = [];
	private bool $useEfficiency;

	/**
	 * Allows a creator to determine how quickly an item can dig specific blocks.
	 * @param bool $useEfficiency Determines whether the item should be impacted by the Efficiency enchantment.
	 * @param array<int, array{
	 *    block: array{
	 * 	   name?: string,
	 * 	   states?: array<string, mixed>,
	 * 	   tags?: string
	 *    },
	 *   speed: int
	 * }> $destroySpeeds An array of blocks/tags and their corresponding digging speeds.
	 */
	public function __construct(bool $useEfficiency = false, array $destroySpeeds = []) {
		$this->useEfficiency = $useEfficiency;
		$this->destroySpeeds = $destroySpeeds;
	}

	public function getName(): string {
		return 'minecraft:digger';
	}

	public function getValue(): array {
		return [
			"use_efficiency" => $this->useEfficiency,
			"destroy_speeds" => $this->destroySpeeds
		];
	}

	public function getPropertyMapping(): ?array {
		return null;
	}

	 /**
	 * Adds blocks to the `destroy_speeds` array with a specified speed.
	 * @param int $speed Digging speed for the correlating block(s)
	 * @param Block ...$blocks A list of blocks to dig with the given speed
	 */
	public function withBlocks(int $speed, Block ...$blocks): self {
		foreach($blocks as $block){
			$this->destroySpeeds[] = [
				"block" => [
					"name" => GlobalBlockStateHandlers::getSerializer()->serialize($block->getStateId())->getName(),
					"states" => [],
					"tags" => ""
				],
				"speed" => $speed
			];
		}
		return $this;
	}

	/**
	 * Adds block tags to the `destroy_speeds` array with a specified speed.
	 * @param int $speed Digging speed for the correlating blocks
	 * @param string ...$tags A list of block tags
	 */
	public function withTags(int $speed, string ...$tags): self {
		$query = implode(", ", array_map(fn(string $tag) => "'" . $tag . "'", $tags));
		$this->destroySpeeds[] = [
			"block" => [
				"name" => "",
				"states" => [],
				"tags" => "query.any_tag($query)"
			],
			"speed" => $speed
		];
		return $this;
	}

	/**
	 * @todo: Implement this properly
	 * 
	 * Adds block states to the `destroy_speeds` array with a specified speed.
	 * @param int $speed Digging speed
	 * @param Block $block Block identifier
	 * @param BlockState ...$states A list of block states with their current values set appropriately
	 */
	public function withStates(int $speed, Block $block, BlockState ...$states) : self {
		$serializedBlock = GlobalBlockStateHandlers::getSerializer()->serialize($block->getStateId());
		$blockStates = [];
		if($block instanceof BlockPermutations){
			foreach($block->getStates() as $state){
				$value = $state->getCurrentValue();
				if(is_bool($value)){
					$value = $value ? 1 : 0;
				}
				$blockStates[$state->getName()] = $value;
			}
		}
		foreach($states as $state){
			$value = $state->getCurrentValue();
			if(is_bool($value)){
				$value = $value ? 1 : 0;
			}
			$blockStates[$state->getName()] = $value;
		}
		$this->destroySpeeds[] = [
			"block" => [
				"name" => $serializedBlock->getName(),
				"states" => $blockStates,
				"tags" => ""
			],
			"speed" => $speed
		];
		return $this;
	}

	/**
	 * Returns the array of destroy speeds.
	 * @return array<int, array{block: array{name?: string, states?: array<string, mixed>, tags?: string}, speed: int}> The destroy speeds array.
	 */
	public function getDestroySpeeds(): array {
		return $this->destroySpeeds;
	}
}