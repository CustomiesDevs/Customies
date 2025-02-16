<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class CooldownComponent implements ItemComponent {

	public const CATEGORY_SHIELD = "minecraft:shield";
	public const CATEGORY_PEARL = "minecraft:ender_pearl";
	public const CATEGORY_HORN = "minecraft:goat_horn";
	public const CATEGORY_WINDCHARGE = "minecraft:wind_charge";
	public const CATEGORY_CHORUS = "minecraft:chorusfruit";

	private string $category;
	private float $duration;

	/**
	 * Sets an item's "Cooldown" time. 
	 * After using an item, it becomes unusable for the duration specified by the `duration` setting of this component.
	 * @param string $category The type of cool down for this item. All items with a cool down component with the same category are put on cool down when one is used
	 * @param float $duration The duration of time (in seconds) items with a matching category will spend cooling down before becoming usable again
	 */
	public function __construct(string $category, float $duration) {
		$this->category = $category;
		$this->duration = $duration;
	}

	public function getName(): string {
		return "minecraft:cooldown";
	}

	public function getValue(): array {
		return [
			"category" => $this->category,
			"duration" => $this->duration
		];
	}

	public function isProperty(): bool {
		return false;
	}
}