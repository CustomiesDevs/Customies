<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class UseAnimationComponent implements ItemComponent {

	public const ANIMATION_NONE = 0;
	public const ANIMATION_EAT = 1;
	public const ANIMATION_DRINK = 2;
	public const ANIMATION_BLOCK = 3;
	public const ANIMATION_BOW = 4;
	public const ANIMATION_CAMERA = 5;
	public const ANIMATION_SPEAR = 6;
	public const ANIMATION_CROSSBOW = 9;
	public const ANIMATION_SPYGLASS = 10;
	public const ANIMATION_BRUSH = 12;

	private int $animation;

	/**
	 * Determines which animation plays when using an item.
	 * @param int $animation Specifies which animation to play when the the item is used, Default is set to `0`
	 */
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