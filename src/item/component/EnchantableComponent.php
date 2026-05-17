<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

use pocketmine\nbt\tag\ByteTag;

final class EnchantableComponent implements ItemComponent {

	// Item Type
	public const SLOT_NONE = "none";
	public const SLOT_ALL = "all";
	public const SLOT_ARMOR = "g_armor";
	public const SLOT_HELMET = "armor_head";
	public const SLOT_CHESTPLATE = "armor_torso";
	public const SLOT_LEGGINGS = "armor_legs";
	public const SLOT_BOOTS = "armor_feet";
	public const SLOT_SWORD = "sword";
	public const SLOT_BOW = "bow";
	public const SLOT_SPEAR = "spear";
	public const SLOT_MELEE_SPEAR = "melee_spear";
	public const SLOT_CROSSBOW = "crossbow";
	public const SLOT_TOOL_GENERIC = "g_tool";
	public const SLOT_HOE = "hoe";
	public const SLOT_SHEARS = "shears";
	public const SLOT_FLINTSTEEL = "flintsteel";
	public const SLOT_SHIELD = "shield";
	public const SLOT_DIGGING = "g_digging";
	public const SLOT_AXE = "axe";
	public const SLOT_PICKAXE = "pickaxe";
	public const SLOT_SHOVEL = "shovel";
	public const SLOT_FISHING_ROD = "fishing_rod";
	public const SLOT_CARROT_STICK = "carrot_stick";
	public const SLOT_ELYTRA = "elytra";
	public const SLOT_COSMETIC_HEAD = "cosmetic_head";

	// Armor Enchantability
	public const ARMOR_LEATHER = 15;
	public const ARMOR_CHAIN = 12;
	public const ARMOR_COPPER = 8;
	public const ARMOR_IRON = 9;
	public const ARMOR_GOLD = 25;
	public const ARMOR_DIAMOND = 10;
	public const ARMOR_TURTLE = 9;
	public const ARMOR_NETHERITE = 15;
	public const ARMOR_OTHER = 1;

	// Tool Enchantability
	public const TOOL_WOOD = 15;
	public const TOOL_STONE = 5;
	public const TOOL_COOPER = 13;
	public const TOOL_IRON = 14;
	public const TOOL_GOLD = 22;
	public const TOOL_DIAMOND = 10;
	public const TOOL_NETHERITE = 15;
	public const TOOL_MACE = 15;
	public const TOOL_OTHER = 1;

	private string $slot;
	private int $value;	

	/**
	 * Determines what enchantments can be applied to the item. Not all enchantments will have an effect on all item components.
	 * @param string $slot Specifies which types of enchantments can be applied. For example, `bow` would allow this item to be enchanted as if it were a bow
	 * @param int $value Specifies the value of the enchantment.
	 * @throws \InvalidArgumentException if the value is not between 0 and 32767.
	 */
	public function __construct(string $slot = self::SLOT_NONE, int $value = 0) {
		if($value < 0 || $value > 32767){
			throw new \InvalidArgumentException("Enchantable value must be between 0 and 32767, $value given");
		}
		$this->slot = $slot;
		$this->value = $value;
	}

	public function getName(): string {
		return 'minecraft:enchantable';
	}

	public function getValue(): array {
		return [
			"slot" => $this->slot,
			"value" => new ByteTag($this->value)
		];
	}

	public function getPropertyMapping(): ?array {
		return ['enchantable_slot' => (string) $this->slot, 'enchantable_value' => (int) $this->value];
	}
}