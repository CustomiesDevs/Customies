<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class ThrowableComponent implements ItemComponent {

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