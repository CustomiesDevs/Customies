<?php
declare(strict_types=1);

namespace customiesdevs\customies\Items\Components;

use customiesdevs\customies\Items\Components\ItemComponentBase;final class UseAnimationComponent implements ItemComponentBase {

	public const ANIMATION_EAT = 1;
	public const ANIMATION_DRINK = 2;

	private mixed $animation;

	public function __construct(mixed $animation) {
		$this->animation = $animation;
	}

	public function getName(): string {
		return "use_animation";
	}

	public function getValue(): mixed {
		return $this->animation;
	}

	public function isProperty(): bool {
		return true;
	}
}