<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class WearableComponent implements ItemComponent {

	public const SLOT_ARMOR_HEAD = "slot.armor.head";
	public const SLOT_ARMOR_CHEST = "slot.armor.chest";
	public const SLOT_ARMOR_LEGS = "slot.armor.legs";
	public const SLOT_ARMOR_FEET = "slot.armor.feet";
	public const SLOT_BODY = "slot.armor.body";
	public const SLOT_WEAPON_MAIN_HAND = "slot.weapon.mainhand";
	public const SLOT_WEAPON_OFF_HAND = "slot.weapon.offhand";

	public const SLOT_HOTBAR = "slot.hotbar";
	public const SLOT_INVENTORY = "slot.inventory";
	public const SLOT_ENDERCHEST = "slot.enderchest";
	public const SLOT_SADDLE = "slot.saddle";
	public const SLOT_ARMOR = "slot.armor";
	public const SLOT_CHEST = "slot.chest";
	public const SLOT_EQUIPPABLE = "slot.equippable";

	private string $slot;
	private int $protection;
	private bool $hidePlayerLocation;

	/**
	 * Sets the wearable item component.
	 * @param string $slot Specifies where the item can be worn. If any non-hand slot is chosen, the max stack size is set to 1.
	 * @param int $protection How much protection the wearable item provides. Default is set to 0.
	 * @param bool $hidePlayerLocation Determines whether the Player's location is hidden on Locator Maps and the Locator Bar when the wearable item is worn. Default is false.
	 */
	public function __construct(string $slot, int $protection = 0, bool $hidePlayerLocation = false) {
		$this->slot = $slot;
		$this->protection = $protection;
		$this->hidePlayerLocation = $hidePlayerLocation;
	}

	public function getName(): string {
		return 'minecraft:wearable';
	}

	public function getValue(): array {
		return [
			"slot" => $this->slot,
			"protection" => $this->protection,
			"hides_player_location" => $this->hidePlayerLocation
		];
	}

	public function getPropertyMapping(): ?array {
		return null;
	}
}