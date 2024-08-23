<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

use pocketmine\block\Block;
use pocketmine\world\format\io\GlobalBlockStateHandlers;
use function array_map;
use function implode;

final class MiningSpeedComponent implements ItemComponent {

	private int $miningSpeed;

    public function __construct(int $mspeed) {
        $this->miningSpeed = $mspeed;
    }
  
	public function getName(): string {
		return "minecraft:mining_speed";
	}

	public function getValue(): int {
		return $this->miningSpeed;
	}

	public function isProperty(): bool {
		return false;
    }
}
