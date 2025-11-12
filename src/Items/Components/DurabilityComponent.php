<?php
declare(strict_types=1);

namespace customiesdevs\customies\Items\Components;

use customiesdevs\customies\Items\Components\ItemComponentBase;final class DurabilityComponent implements ItemComponentBase {

	private int $maxDurability;

	public function __construct(int $maxDurability) {
		$this->maxDurability = $maxDurability;
	}

	public function getName(): string {
		return "minecraft:durability";
	}

	public function getValue(): array {
		return [
			"max_durability" => $this->maxDurability
		];
	}

	public function isProperty(): bool {
		return false;
	}
}