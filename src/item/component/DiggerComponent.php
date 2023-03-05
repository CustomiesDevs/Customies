<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

use pocketmine\block\Block;
use pocketmine\world\format\io\GlobalBlockStateHandlers;

use function array_map;
use function implode;

final class DiggerComponent extends BasicComponent
{

	/** @var array $destroySpeeds */
	private array $destroySpeeds = [];

	public function __construct()
	{
		parent::__construct("minecraft:digger", [
			"destroy_speeds" => $this->destroySpeeds
		], false);
	}

	public function withBlocks(int $speed, Block ...$blocks): DiggerComponent
	{
		foreach ($blocks as $block)
			$this->destroySpeeds[] = [
				"block" => [
					"name" => GlobalBlockStateHandlers::getSerializer()->serialize($block->getStateId())->getName()
				],
				"speed" => $speed
			];

		return $this;
	}

	public function withTags(int $speed, string ...$tags): DiggerComponent
	{
		$this->destroySpeeds[] = [
			"block" => [
				"tags" => "query.any_tag(" . implode(",", array_map(fn($tag) => "'" . $tag . "'", $tags)) . ")"
			],
			"speed" => $speed
		];

		return $this;
	}
}