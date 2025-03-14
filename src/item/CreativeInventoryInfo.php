<?php

namespace customiesdevs\customies\item;

final class CreativeInventoryInfo {

	public const CATEGORY_ALL = "all";
	public const CATEGORY_COMMANDS = "commands";
	public const CATEGORY_CONSTRUCTION = "construction";
	public const CATEGORY_EQUIPMENT = "equipment";
	public const CATEGORY_ITEMS = "items";
	public const CATEGORY_NATURE = "nature";

	public const NONE = "none";
	public const GROUP_ANVIL = "minecraft:itemGroup.name.anvil";
	public const GROUP_ARROW = "minecraft:itemGroup.name.arrow";
	public const GROUP_AXE = "minecraft:itemGroup.name.axe";
	public const GROUP_BANNER = "minecraft:itemGroup.name.banner";
	public const GROUP_BANNER_PATTERN = "minecraft:itemGroup.name.banner_pattern";
	public const GROUP_BED = "minecraft:itemGroup.name.bed";
	public const GROUP_BOAT = "minecraft:itemGroup.name.boat";
	public const GROUP_BOOTS = "minecraft:itemGroup.name.boots";
	public const GROUP_BUTTONS = "minecraft:itemGroup.name.buttons";
	public const GROUP_CHALKBOARD = "minecraft:itemGroup.name.chalkboard";
	public const GROUP_CHEST = "minecraft:itemGroup.name.chest";
	public const GROUP_CHESTPLATE = "minecraft:itemGroup.name.chestplate";
	public const GROUP_CONCRETE = "minecraft:itemGroup.name.concrete";
	public const GROUP_CONCRETE_POWDER = "minecraft:itemGroup.name.concretePowder";
	public const GROUP_COOKED_FOOD = "minecraft:itemGroup.name.cookedFood";
	public const GROUP_COOPPER = "minecraft:itemGroup.name.copper";
	public const GROUP_CORAL = "minecraft:itemGroup.name.coral";
	public const GROUP_CORAL_DECORATIONS = "minecraft:itemGroup.name.coral_decorations";
	public const GROUP_CROP = "minecraft:itemGroup.name.crop";
	public const GROUP_DOOR = "minecraft:itemGroup.name.door";
	public const GROUP_DYE = "minecraft:itemGroup.name.dye";
	public const GROUP_ENCHANTED_BOOK = "minecraft:itemGroup.name.enchantedBook";
	public const GROUP_FENCE = "minecraft:itemGroup.name.fence";
	public const GROUP_FENCE_GATE = "minecraft:itemGroup.name.fenceGate";
	public const GROUP_FIREWORK = "minecraft:itemGroup.name.firework";
	public const GROUP_FIREWORK_STARS = "minecraft:itemGroup.name.fireworkStars";
	public const GROUP_FLOWER = "minecraft:itemGroup.name.flower";
	public const GROUP_GLASS = "minecraft:itemGroup.name.glass";
	public const GROUP_GLASS_PANE = "minecraft:itemGroup.name.glassPane";
	public const GROUP_GLAZED_TERRACOTTA = "minecraft:itemGroup.name.glazedTerracotta";
	public const GROUP_GRASS = "minecraft:itemGroup.name.grass";
	public const GROUP_HELMET = "minecraft:itemGroup.name.helmet";
	public const GROUP_HOE = "minecraft:itemGroup.name.hoe";
	public const GROUP_HORSE_ARMOR = "minecraft:itemGroup.name.horseArmor";
	public const GROUP_LEAVES = "minecraft:itemGroup.name.leaves";
	public const GROUP_LEGGINGS = "minecraft:itemGroup.name.leggings";
	public const GROUP_LINGERING_POTION = "minecraft:itemGroup.name.lingeringPotion";
	public const GROUP_LOG = "minecraft:itemGroup.name.log";
	public const GROUP_MINECRAFT = "minecraft:itemGroup.name.minecart";
	public const GROUP_MISC_FOOD = "minecraft:itemGroup.name.miscFood";
	public const GROUP_MOB_EGGS = "minecraft:itemGroup.name.mobEgg";
	public const GROUP_MONSTER_STONE_EGG = "minecraft:itemGroup.name.monsterStoneEgg";
	public const GROUP_MUSHROOM = "minecraft:itemGroup.name.mushroom";
	public const GROUP_NETHERWART_BLOCK = "minecraft:itemGroup.name.netherWartBlock";
	public const GROUP_ORE = "minecraft:itemGroup.name.ore";
	public const GROUP_PERMISSION = "minecraft:itemGroup.name.permission";
	public const GROUP_PICKAXE = "minecraft:itemGroup.name.pickaxe";
	public const GROUP_PLANKS = "minecraft:itemGroup.name.planks";
	public const GROUP_POTION = "minecraft:itemGroup.name.potion";
	public const GROUP_PRESSURE_PLATE = "minecraft:itemGroup.name.pressurePlate";
	public const GROUP_RAIL = "minecraft:itemGroup.name.rail";
	public const GROUP_RAW_FOOD = "minecraft:itemGroup.name.rawFood";
	public const GROUP_RECORD = "minecraft:itemGroup.name.record";
	public const GROUP_SANDSTONE = "minecraft:itemGroup.name.sandstone";
	public const GROUP_SAPLING = "minecraft:itemGroup.name.sapling";
	public const GROUP_SEED = "minecraft:itemGroup.name.seed";
	public const GROUP_SHOVEL = "minecraft:itemGroup.name.shovel";
	public const GROUP_SHULKER_BOX = "minecraft:itemGroup.name.shulkerBox";
	public const GROUP_SIGN = "minecraft:itemGroup.name.sign";
	public const GROUP_SKULL = "minecraft:itemGroup.name.skull";
	public const GROUP_SLAB = "minecraft:itemGroup.name.slab";
	public const GROUP_SLASH_POTION = "minecraft:itemGroup.name.splashPotion";
	public const GROUP_STAINED_CLAY = "minecraft:itemGroup.name.stainedClay";
	public const GROUP_STAIRS = "minecraft:itemGroup.name.stairs";
	public const GROUP_STONE = "minecraft:itemGroup.name.stone";
	public const GROUP_STONE_BRICK = "minecraft:itemGroup.name.stoneBrick";
	public const GROUP_SWORD = "minecraft:itemGroup.name.sword";
	public const GROUP_TRAPDOOR = "minecraft:itemGroup.name.trapdoor";
	public const GROUP_WALLS = "minecraft:itemGroup.name.walls";
	public const GROUP_WOOD = "minecraft:itemGroup.name.wood";
	public const GROUP_WOOL = "minecraft:itemGroup.name.wool";
	public const GROUP_WOOL_CARPET = "minecraft:itemGroup.name.woolCarpet";
	public const GROUP_CANDLES = "minecraft:itemGroup.name.candles";
	public const GROUP_GOAT_HORN = "minecraft:itemGroup.name.goatHorn";

	/**
	 * Returns a default type which puts the item in to the all category and no sub group.
	 */
	public static function DEFAULT(): self {
		return new self(self::CATEGORY_ALL, self::NONE);
	}

	public function __construct(private readonly string $category = self::NONE, private readonly string $group = self::NONE) { }

	/**
	 * Returns the category the item is part of.
	 */
	public function getCategory(): string {
		return $this->category;
	}

	/**
	 * Returns the numeric representation of the category the item is part of.
	 * @return int Default is set to `0`
	 */
	public function getNumericCategory(): int {
		return match ($this->getCategory()) {
			self::CATEGORY_CONSTRUCTION => 1,
			self::CATEGORY_NATURE => 2,
			self::CATEGORY_EQUIPMENT => 3,
			self::CATEGORY_ITEMS => 4,
			default => 0
		};
	}

	/**
	 * Returns the group the item is part of, if any.
	 */
	public function getGroup(): string {
		return $this->group;
	}
}
