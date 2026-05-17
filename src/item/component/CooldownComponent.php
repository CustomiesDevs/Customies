<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class CooldownComponent implements ItemComponent {

	public const CATEGORY_SHIELD = "shield";
	public const CATEGORY_PEARL = "ender_pearl";
	public const CATEGORY_HORN = "goat_horn";
	public const CATEGORY_WINDCHARGE = "wind_charge";
	public const CATEGORY_CHORUS = "chorusfruit";

	/** 
	 * Causes the cooldown to start when the player attacks while holding the item and
	 * prevents the item from being used to attack while the cooldown is active. 
	 */
	public const TYPE_ATTACK = "attack";
	/** 
	 * Causes the cooldown to start when the item is used and
	 * prevents the item from being used while the cooldown is active.
	 */
	public const TYPE_USE = "use";

	private string $category;
	private float $duration;
	private string $type;

	/**
	 * The duration of time (in seconds) items with a matching category will spend cooling down before becoming usable again.
	 * @param string $category All items with the same "category" are put on cooldown when one is used.
	 * @param float $duration How long the item is on cooldown before being able to be used again.
	 * @param string $type The type of cooldown (e.g., "use", "attack"). Default is "use".
	 */
	public function __construct(string $category, float $duration, string $type = self::TYPE_USE) {
		$this->category = $category;
		$this->duration = $duration;
		$this->type = $type;
	}

	public function getName(): string {
		return 'minecraft:cooldown';
	}

	public function getValue(): array {
		return [
			"category" => $this->category,
			"duration" => $this->duration,
			"type" => $this->type,
		];
	}

	public function getPropertyMapping(): ?array {
		return null;
	}
}