<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

use customiesdevs\customies\world\LegacyBlockIdToStringIdMap;
use pocketmine\block\Block;
use function array_map;
use function implode;

final class DiggerComponent implements ItemComponent {

	private array $destroySpeeds;

	public function getName(): string {
		return "minecraft:digger";
	}

	public function getValue(): array {
		return [
			"destroy_speeds" => $this->destroySpeeds
		];
	}

	public function isProperty(): bool {
		return false;
	}

	public function withBlocks(int $speed, Block ...$blocks): DiggerComponent {
		foreach($blocks as $block){
			$this->destroySpeeds[] = [
				"block" => [
					"name" => LegacyBlockIdToStringIdMap::getInstance()->legacyToString($block->getId())
				],
				"speed" => $speed
			];
		}
		return $this;
	}

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