<?php

namespace customiesdevs\customies\item;

final class CreativeInventoryInfo {

	const CATEGORY_ALL = "all";
	const CATEGORY_COMMANDS = "commands";
	const CATEGORY_CONSTRUCTION = "construction";
	const CATEGORY_EQUIPMENT = "equipment";
	const CATEGORY_ITEMS = "items";
	const CATEGORY_NATURE = "nature";

	const NONE = "none";
	const GROUP_ANVIL = "itemGroup.name.anvil";
	const GROUP_ARROW = "itemGroup.name.arrow";
	const GROUP_AXE = "itemGroup.name.axe";
	const GROUP_BANNER = "itemGroup.name.banner";
	const GROUP_BANNER_PATTERN = "itemGroup.name.banner_pattern";
	const GROUP_BED = "itemGroup.name.bed";
	const GROUP_BOAT = "itemGroup.name.boat";
	const GROUP_BOOTS = "itemGroup.name.boots";
	const GROUP_BUTTONS = "itemGroup.name.buttons";
	const GROUP_CHALKBOARD = "itemGroup.name.chalkboard";
	const GROUP_CHEST = "itemGroup.name.chest";
	const GROUP_CHESTPLATE = "itemGroup.name.chestplate";
	const GROUP_CONCRETE = "itemGroup.name.concrete";
	const GROUP_CONCRETE_POWDER = "itemGroup.name.concretePowder";
	const GROUP_COOKED_FOOD = "itemGroup.name.cookedFood";
	const GROUP_COOPPER = "itemGroup.name.copper";
	const GROUP_CORAL = "itemGroup.name.coral";
	const GROUP_CORAL_DECORATIONS = "itemGroup.name.coral_decorations";
	const GROUP_CROP = "itemGroup.name.crop";
	const GROUP_DOOR = "itemGroup.name.door";
	const GROUP_DYE = "itemGroup.name.dye";
	const GROUP_ENCHANTED_BOOK = "itemGroup.name.enchantedBook";
	const GROUP_FENCE = "itemGroup.name.fence";
	const GROUP_FENCE_GATE = "itemGroup.name.fenceGate";
	const GROUP_FIREWORK = "itemGroup.name.firework";
	const GROUP_FIREWORK_STARS = "itemGroup.name.fireworkStars";
	const GROUP_FLOWER = "itemGroup.name.flower";
	const GROUP_GLASS = "itemGroup.name.glass";
	const GROUP_GLASS_PANE = "itemGroup.name.glassPane";
	const GROUP_GLAZED_TERRACOTTA = "itemGroup.name.glazedTerracotta";
	const GROUP_GRASS = "itemGroup.name.grass";
	const GROUP_HELMET = "itemGroup.name.helmet";
	const GROUP_HOE = "itemGroup.name.hoe";
	const GROUP_HORSE_ARMOR = "itemGroup.name.horseArmor";
	const GROUP_LEAVES = "itemGroup.name.leaves";
	const GROUP_LEGGINGS = "itemGroup.name.leggings";
	const GROUP_LINGERING_POTION = "itemGroup.name.lingeringPotion";
	const GROUP_LOG = "itemGroup.name.log";
	const GROUP_MINECRAFT = "itemGroup.name.minecart";
	const GROUP_MISC_FOOD = "itemGroup.name.miscFood";
	const GROUP_MOB_EGGS = "itemGroup.name.mobEgg";
	const GROUP_MONSTER_STONE_EGG = "itemGroup.name.monsterStoneEgg";
	const GROUP_MUSHROOM = "itemGroup.name.mushroom";
	const GROUP_NETHERWART_BLOCK = "itemGroup.name.netherWartBlock";
	const GROUP_ORE = "itemGroup.name.ore";
	const GROUP_PERMISSION = "itemGroup.name.permission";
	const GROUP_PICKAXE = "itemGroup.name.pickaxe";
	const GROUP_PLANKS = "itemGroup.name.planks";
	const GROUP_POTION = "itemGroup.name.potion";
	const GROUP_PRESSURE_PLATE = "itemGroup.name.pressurePlate";
	const GROUP_RAIL = "itemGroup.name.rail";
	const GROUP_RAW_FOOD = "itemGroup.name.rawFood";
	const GROUP_RECORD = "itemGroup.name.record";
	const GROUP_SANDSTONE = "itemGroup.name.sandstone";
	const GROUP_SAPLING = "itemGroup.name.sapling";
	const GROUP_SEED = "itemGroup.name.seed";
	const GROUP_SHOVEL = "itemGroup.name.shovel";
	const GROUP_SHULKER_BOX = "itemGroup.name.shulkerBox";
	const GROUP_SIGN = "itemGroup.name.sign";
	const GROUP_SKULL = "itemGroup.name.skull";
	const GROUP_SLAB = "itemGroup.name.slab";
	const GROUP_SLASH_POTION = "itemGroup.name.splashPotion";
	const GROUP_STAINED_CLAY = "itemGroup.name.stainedClay";
	const GROUP_STAIRS = "itemGroup.name.stairs";
	const GROUP_STONE = "itemGroup.name.stone";
	const GROUP_STONE_BRICK = "itemGroup.name.stoneBrick";
	const GROUP_SWORD = "itemGroup.name.sword";
	const GROUP_TRAPDOOR = "itemGroup.name.trapdoor";
	const GROUP_WALLS = "itemGroup.name.walls";
	const GROUP_WOOD = "itemGroup.name.wood";
	const GROUP_WOOL = "itemGroup.name.wool";
	const GROUP_WOOL_CARPET = "itemGroup.name.woolCarpet";
	const GROUP_CANDLES = "itemGroup.name.candles";
	const GROUP_GOAT_HORN = "itemGroup.name.goatHorn";

	/**
	 * Returns a default type which puts the item in to the all category and no sub group.
	 */
	public static function DEFAULT(): self {
		return new self(self::CATEGORY_ALL, self::NONE);
	}

	private string $category;
	private string $group;

	public function __construct(string $category = self::NONE, string $group = self::NONE) {
		$this->category = $category;
		$this->group = $group;
	}

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
