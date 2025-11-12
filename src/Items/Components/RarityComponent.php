<?php
declare(strict_types=1);

namespace customiesdevs\customies\Items\Components;

use customiesdevs\customies\Items\Components\ItemComponentBase;final class RarityComponent implements ItemComponentBase {

	private string $rarity;

	public function __construct(string $rarity) {
		$this->rarity = $rarity;
	}

	public function getName(): string {
		return "minecraft:rarity"; //common, uncommon, rare, epic
	}

	public function getValue(): array {
		return [
            "value" => $this->rarity
        ];
	}

	public function isProperty(): bool {
		return false;
	}
}