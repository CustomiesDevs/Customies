<?php
declare(strict_types=1);

namespace customiesdevs\customies\Items\Components;

use customiesdevs\customies\Items\Components\ItemComponentBase;final class ThrowableComponent implements ItemComponentBase {

	private bool $doSwingAnimation;

	public function __construct(bool $doSwingAnimation) {
		$this->doSwingAnimation = $doSwingAnimation;
	}

	public function getName(): string {
		return "minecraft:throwable";
	}

	public function getValue(): array {
		return [
			"do_swing_animation" => $this->doSwingAnimation
		];
	}

	public function isProperty(): bool {
		return false;
	}
}