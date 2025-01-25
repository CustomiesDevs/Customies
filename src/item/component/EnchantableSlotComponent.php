<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class EnchantableSlotComponent implements ItemComponent {

	public const SLOT_ALL = "all";
	public const SLOT_BOOTS = "armor_feet";
	public const SLOT_CHESTPLATE = "armor_torso";
	public const SLOT_HELMET = "armor_head";
	public const SLOT_LEGGINGS = "armor_legs";
	public const SLOT_AXE = "axe";
	public const SLOT_BOW = "bow";
	public const SLOT_COSMETIC_HEAD = "cosmetic_head";
	public const SLOT_CROSSBOW = "crossbow";
	public const SLOT_ELYTRA = "elytra";
	public const SLOT_FISHING_ROD = "fishing_rod";
	public const SLOT_FLINT = "flintsteel";
	public const SLOT_HOE = "hoe";
	public const SLOT_PICKAXE = "pickaxe";
	public const SLOT_SHEARS = "shears";
	public const SLOT_SHIELD = "shield";
	public const SLOT_SHOVEL = "shovel";
	public const SLOT_SWORD = "sword";

	private string $slot;

	/**
	 * What enchantments can be applied (ex. Using bow would allow this item to be enchanted as if it were a bow).
	 * @param string $slot Specifies which types of enchantments can be applied. For example, `bow` would allow this item to be enchanted as if it were a bow
	 */
	public function __construct(string $slot = self::SLOT_ALL) {
		$this->slot = $slot;
	}

	public function getName(): string {
		return "enchantable_slot";
	}

	public function getValue(): string {
		return $this->slot;
	}

	public function isProperty(): bool {
		return true;
	}
}