<?php
declare(strict_types=1);

namespace customiesdevs\customies\Items\Components;

use customiesdevs\customies\Items\Components\ItemComponentBase;final class FoodComponent implements ItemComponentBase {

	private bool $canAlwaysEat;

	public function __construct(bool $canAlwaysEat = false) {
		$this->canAlwaysEat = $canAlwaysEat;
	}

	public function getName(): string {
		return "minecraft:food";
	}

	public function getValue(): array {
		return [
			"can_always_eat" => $this->canAlwaysEat
		];
	}

	public function isProperty(): bool {
		return false;
	}
}