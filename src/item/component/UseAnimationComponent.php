<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class UseAnimationComponent implements ItemComponent {

	public const ANIMATION_NONE = 'none';
	public const ANIMATION_EAT = 'eat';
	public const ANIMATION_DRINK = 'drink';
	public const ANIMATION_BLOCK = 'block';
	public const ANIMATION_BOW = 'bow';
	public const ANIMATION_CAMERA = 'camera';
	public const ANIMATION_SPEAR = 'spear';
	public const ANIMATION_CROSSBOW = 'crossbow';
	public const ANIMATION_SPYGLASS = 'spyglass';
	public const ANIMATION_BRUSH = 'brush';

	private const STRING_TO_INT = [
		self::ANIMATION_NONE => 0,
		self::ANIMATION_EAT => 1,
		self::ANIMATION_DRINK => 2,
		self::ANIMATION_BLOCK => 3,
		self::ANIMATION_BOW => 4,
		self::ANIMATION_CAMERA => 5,
		self::ANIMATION_SPEAR => 6,
		self::ANIMATION_CROSSBOW => 9,
		self::ANIMATION_SPYGLASS => 10,
		self::ANIMATION_BRUSH => 12,
	];

	private string $animation;

	/**
	 * Determines which animation plays when using an item.
	 * @param string $animation Specifies which animation to play when the the item is used.
	 */
	public function __construct(string $animation = self::ANIMATION_NONE) {
		$this->animation = $animation;
	}

	public function getName(): string {
		return 'minecraft:use_animation';
	}

	public function getValue(): array {
		return [
			"value" => $this->animation
		];
	}

	public function getPropertyMapping(): ?array {
		return ['use_animation' => (int) self::STRING_TO_INT[$this->animation] ?? 0];
	}
}