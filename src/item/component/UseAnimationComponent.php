<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class UseAnimationComponent implements ItemComponent {

	public const ANIMATION_EAT = 1;
	public const ANIMATION_DRINK = 2;

	private int $animation;

	public function __construct(int $animation) {
		$this->animation = $animation;
	}

	public function getName(): string {
		return "use_animation";
	}

	public function getValue(): int {
		return $this->animation;
	}

	public function isProperty(): bool {
		return true;
	}
}