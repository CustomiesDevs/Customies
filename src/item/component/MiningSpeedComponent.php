<?php

declare(strict_types=1);

namespace customiesdevs\customies\item\component;

use pocketmine\block\Block;
use function array_map;
use function implode;

final class MiningSpeedComponent implements ItemComponent {

	private array $destroySpeeds;
	private const TOOL_TYPES = ["wood", "stone", "iron", "gold", "diamond"];

        public function __construct(private string $toolType = "wood", private int $mspeed) {
		if (!in_array($this->toolType, self::TOOL_TYPES)) {
			throw new Exception('Tool type not listed, default is wood!');
		}

		$this->withBlocks($this->mspeed,
			"Andesite", 
			"Andesite Wall", 
			"Brick Slab", 
			"Brick Wall", 
			"Bricks", 
			"Cobblestone", 
			"Cobblestone Slab", 
			"Cobblestone Stairs", 
			"Cobblestone Wall", 
			"Diorite", 
			"Diorite Wall", 
			"Dripstone Block", 
			"End Stone Brick Wall", 
			"Granite", 
			"Granite Wall", 
			"Mossy Cobblestone", 
			"Mossy Cobblestone Wall", 
			"Nether Brick Wall", 
			"Polished Andesite", 
			"Polished Diorite", 
			"Polished Granite", 
			"Prismarine Wall", 
			"Quartz Slab", 
			"Red Nether Brick Wall", 
			"Red Sandstone Wall", 
			"Sandstone Slab", 
			"Sandstone Wall", 
			"Smooth Stone Slab", 
			"Stone", 
			"Stone Brick Wall", 
			"Stone Bricks Slab"
			);
		
                $this->withTags($this->mspeed, 'metal', $this->toolType.'_pick_diggable'));
        }
  
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

	public function withBlocks(int $speed, string ...$blocks): DiggerComponent {
		foreach($blocks as $block){
			$this->destroySpeeds[] = [
				"block" => [
					"name" => "minecraft:". str_replace(" ", "_", strtolower($block))
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
