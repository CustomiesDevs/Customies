<?php

namespace customiesdevs\customies\item;

final class CreativeInventoryInfo {

	const string CATEGORY_ALL = "all";
	const string CATEGORY_COMMANDS = "commands";
	public const string CATEGORY_CONSTRUCTION = "construction";
	public const string CATEGORY_EQUIPMENT = "equipment";
	const string CATEGORY_ITEMS = "items";
	const string CATEGORY_NATURE = "nature";

	public const string NONE = "none";
	const string GROUP_ANVIL = "itemGroup.name.anvil";
	const string GROUP_ARROW = "itemGroup.name.arrow";
	const string GROUP_AXE = "itemGroup.name.axe";
	const string GROUP_BANNER = "itemGroup.name.banner";
	const string GROUP_BANNER_PATTERN = "itemGroup.name.banner_pattern";
	const string GROUP_BED = "itemGroup.name.bed";
	const string GROUP_BOAT = "itemGroup.name.boat";
	const string GROUP_BOOTS = "itemGroup.name.boots";
	const string GROUP_BUTTONS = "itemGroup.name.buttons";
	const string GROUP_CHALKBOARD = "itemGroup.name.chalkboard";
	const string GROUP_CHEST = "itemGroup.name.chest";
	const string GROUP_CHESTPLATE = "itemGroup.name.chestplate";
	const string GROUP_CONCRETE = "itemGroup.name.concrete";
	const string GROUP_CONCRETE_POWDER = "itemGroup.name.concretePowder";
	const string GROUP_COOKED_FOOD = "itemGroup.name.cookedFood";
	const string GROUP_COOPPER = "itemGroup.name.copper";
	const string GROUP_CORAL = "itemGroup.name.coral";
	const string GROUP_CORAL_DECORATIONS = "itemGroup.name.coral_decorations";
	const string GROUP_CROP = "itemGroup.name.crop";
	const string GROUP_DOOR = "itemGroup.name.door";
	const string GROUP_DYE = "itemGroup.name.dye";
	const string GROUP_ENCHANTED_BOOK = "itemGroup.name.enchantedBook";
	const string GROUP_FENCE = "itemGroup.name.fence";
	const string GROUP_FENCE_GATE = "itemGroup.name.fenceGate";
	const string GROUP_FIREWORK = "itemGroup.name.firework";
	const string GROUP_FIREWORK_STARS = "itemGroup.name.fireworkStars";
	const string GROUP_FLOWER = "itemGroup.name.flower";
	const string GROUP_GLASS = "itemGroup.name.glass";
	const string GROUP_GLASS_PANE = "itemGroup.name.glassPane";
	const string GROUP_GLAZED_TERRACOTTA = "itemGroup.name.glazedTerracotta";
	const string GROUP_GRASS = "itemGroup.name.grass";
	const string GROUP_HELMET = "itemGroup.name.helmet";
	const string GROUP_HOE = "itemGroup.name.hoe";
	const string GROUP_HORSE_ARMOR = "itemGroup.name.horseArmor";
	const string GROUP_LEAVES = "itemGroup.name.leaves";
	const string GROUP_LEGGINGS = "itemGroup.name.leggings";
	const string GROUP_LINGERING_POTION = "itemGroup.name.lingeringPotion";
	const string GROUP_LOG = "itemGroup.name.log";
	const string GROUP_MINECRAFT = "itemGroup.name.minecart";
	const string GROUP_MISC_FOOD = "itemGroup.name.miscFood";
	const string GROUP_MOB_EGGS = "itemGroup.name.mobEgg";
	const string GROUP_MONSTER_STONE_EGG = "itemGroup.name.monsterStoneEgg";
	const string GROUP_MUSHROOM = "itemGroup.name.mushroom";
	const string GROUP_NETHERWART_BLOCK = "itemGroup.name.netherWartBlock";
	const string GROUP_ORE = "itemGroup.name.ore";
	const string GROUP_PERMISSION = "itemGroup.name.permission";
	const string GROUP_PICKAXE = "itemGroup.name.pickaxe";
	const string GROUP_PLANKS = "itemGroup.name.planks";
	const string GROUP_POTION = "itemGroup.name.potion";
	const string GROUP_PRESSURE_PLATE = "itemGroup.name.pressurePlate";
	const string GROUP_RAIL = "itemGroup.name.rail";
	const string GROUP_RAW_FOOD = "itemGroup.name.rawFood";
	const string GROUP_RECORD = "itemGroup.name.record";
	const string GROUP_SANDSTONE = "itemGroup.name.sandstone";
	const string GROUP_SAPLING = "itemGroup.name.sapling";
	const string GROUP_SEED = "itemGroup.name.seed";
	const string GROUP_SHOVEL = "itemGroup.name.shovel";
	const string GROUP_SHULKER_BOX = "itemGroup.name.shulkerBox";
	const string GROUP_SIGN = "itemGroup.name.sign";
	const string GROUP_SKULL = "itemGroup.name.skull";
	const string GROUP_SLAB = "itemGroup.name.slab";
	const string GROUP_SLASH_POTION = "itemGroup.name.splashPotion";
	const string GROUP_STAINED_CLAY = "itemGroup.name.stainedClay";
	const string GROUP_STAIRS = "itemGroup.name.stairs";
	const string GROUP_STONE = "itemGroup.name.stone";
	const string GROUP_STONE_BRICK = "itemGroup.name.stoneBrick";
	const string GROUP_SWORD = "itemGroup.name.sword";
	const string GROUP_TRAPDOOR = "itemGroup.name.trapdoor";
	const string GROUP_WALLS = "itemGroup.name.walls";
	const string GROUP_WOOD = "itemGroup.name.wood";
	const string GROUP_WOOL = "itemGroup.name.wool";
	const string GROUP_WOOL_CARPET = "itemGroup.name.woolCarpet";
	const string GROUP_CANDLES = "itemGroup.name.candles";
	const string GROUP_GOAT_HORN = "itemGroup.name.goatHorn";

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
