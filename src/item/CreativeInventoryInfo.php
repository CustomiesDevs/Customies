<?php

namespace customiesdevs\customies\item;

use pocketmine\block\Block;
use pocketmine\inventory\CreativeCategory;
use pocketmine\inventory\CreativeGroup;
use pocketmine\inventory\CreativeInventory;
use pocketmine\item\Item;
use pocketmine\lang\Translatable;
use pocketmine\utils\AssumptionFailedError;

final class CreativeInventoryInfo {

	/** @var array<string, CreativeGroup>|null */
	private static ?array $groups = null;

	const NONE = "none";

	const CATEGORY_ALL = "all";
	const CATEGORY_COMMANDS = "commands";
	const CATEGORY_CONSTRUCTION = "construction";
	const CATEGORY_EQUIPMENT = "equipment";
	const CATEGORY_ITEMS = "items";
	const CATEGORY_NATURE = "nature";

	const GROUP_ANVIL = "itemGroup.name.anvil";
	const GROUP_ARROW = "itemGroup.name.arrow";
	const GROUP_AXE = "itemGroup.name.axe";
	const GROUP_BANNER = "itemGroup.name.banner";
	const GROUP_BANNER_PATTERN = "itemGroup.name.banner_pattern";
	const GROUP_BAR = "itemGroup.name.bars";
	const GROUP_BED = "itemGroup.name.bed";
	const GROUP_BOAT = "itemGroup.name.boat";
	const GROUP_BOOTS = "itemGroup.name.boots";
	const GROUP_BUNDLES = "itemGroup.name.bundles";
	const GROUP_BUTTONS = "itemGroup.name.buttons";
	const GROUP_CANDLES = "itemGroup.name.candles";
	const GROUP_CHAINS = "itemGroup.name.chains";
	const GROUP_CHALKBOARD = "itemGroup.name.chalkboard";
	const GROUP_CHEST = "itemGroup.name.chest";
	const GROUP_CHEST_BOAT = "itemGroup.name.chestboat";
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
	const GROUP_GOAT_HORN = "itemGroup.name.goatHorn";
	const GROUP_GOLEM_STATUE = "itemGroup.name.copper_golem_statue";
	const GROUP_GRASS = "itemGroup.name.grass";
	const GROUP_HANGING_SIGN = "itemGroup.name.hanging_sign";
	const GROUP_HARNESSES = "itemGroup.name.harnesses";
	const GROUP_HELMET = "itemGroup.name.helmet";
	const GROUP_HOE = "itemGroup.name.hoe";
	const GROUP_HORSE_ARMOR = "itemGroup.name.horseArmor";
	const GROUP_LANTERNS = "itemGroup.name.lanterns";
	const GROUP_LEAVES = "itemGroup.name.leaves";
	const GROUP_LEGGINGS = "itemGroup.name.leggings";
	const GROUP_LIGHTNING_ROD = "itemGroup.name.lightning_rod";
	const GROUP_LINGERING_POTION = "itemGroup.name.lingeringPotion";
	const GROUP_LOG = "itemGroup.name.log";
	const GROUP_MINECRAFT = "itemGroup.name.minecart";
	const GROUP_MISC_FOOD = "itemGroup.name.miscFood";
	const GROUP_MOB_EGGS = "itemGroup.name.mobEgg";
	const GROUP_MONSTER_STONE_EGG = "itemGroup.name.monsterStoneEgg";
	const GROUP_MUSHROOM = "itemGroup.name.mushroom";
	const GROUP_NAUTILUS_ARMOR = "itemGroup.name.nautilus_armor";
	const GROUP_NETHERWART_BLOCK = "itemGroup.name.netherWartBlock";
	const GROUP_OMINOUS_BOTTLE = "itemGroup.name.ominousBottle";
	const GROUP_ORE = "itemGroup.name.ore";
	const GROUP_PERMISSION = "itemGroup.name.permission";
	const GROUP_PICKAXE = "itemGroup.name.pickaxe";
	const GROUP_PLANKS = "itemGroup.name.planks";
	const GROUP_POTION = "itemGroup.name.potion";
	const GROUP_POTTERY_SHERDS = "itemGroup.name.potterySherds";
	const GROUP_PRESSURE_PLATE = "itemGroup.name.pressurePlate";
	const GROUP_RAIL = "itemGroup.name.rail";
	const GROUP_RAW_FOOD = "itemGroup.name.rawFood";
	const GROUP_RECORD = "itemGroup.name.record";
	const GROUP_SANDSTONE = "itemGroup.name.sandstone";
	const GROUP_SAPLING = "itemGroup.name.sapling";
	const GROUP_SCULK = "itemGroup.name.sculk";
	const GROUP_SEED = "itemGroup.name.seed";
	const GROUP_SHELF = "itemGroup.name.shelf";
	const GROUP_SHOVEL = "itemGroup.name.shovel";
	const GROUP_SHULKER_BOX = "itemGroup.name.shulkerBox";
	const GROUP_SIGN = "itemGroup.name.sign";
	const GROUP_SKULL = "itemGroup.name.skull";
	const GROUP_SLAB = "itemGroup.name.slab";
	const GROUP_SLASH_POTION = "itemGroup.name.splashPotion";
	const GROUP_SMITHING_TEMPLATES = "itemGroup.name.smithing_templates";
	const GROUP_SPEAR = "itemGroup.name.spear";
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

	/**
	 * Returns a default CreativeInventoryInfo instance (all category, no group)
	 * @return self
	 */
	public static function DEFAULT(): self {
		return new self(self::CATEGORY_ALL, self::NONE);
	}

	/**
	 * @param string $category The category this item belongs to
	 * @param string $group The group this item belongs to (optional)
	 */
	public function __construct(
		private readonly string $category = self::NONE,
		private readonly string $group = self::NONE
	) {}

	/**
	 * Returns the creative inventory category.
	 * @return string
	 */
	public function getCategory(): string {
		return $this->category;
	}

	/**
	 * Returns the numeric representation of the category.
	 * 0 = all, 1 = construction, 2 = nature, 3 = equipment, 4 = items
	 * @return int
	 */
	public function getNumericCategory(): int {
		return match ($this->category) {
			self::CATEGORY_CONSTRUCTION => 1,
			self::CATEGORY_NATURE => 2,
			self::CATEGORY_EQUIPMENT => 3,
			self::CATEGORY_ITEMS => 4,
			default => 0,
		};
	}

	/**
	 * Returns the creative inventory group.
	 * @return string
	 */
	public function getGroup(): string {
		return $this->group;
	}

	/**
	 * Loads all existing creative groups from the Creative Inventory.
	 * @return void
	 */
	public static function load(): void {
		if(self::$groups !== null){
			return;
		}
		$groups = [];
		foreach(CreativeInventory::getInstance()->getAllEntries() as $entry){
			$group = $entry->getGroup();
			if($group !== null){
				$groups[$group->getName()->getText()] = $group;
			}
		}
		self::$groups = $groups;
	}

	/**
	 * Returns the CreativeGroup instance for the given name, or null if it does not exist.
	 * @param string $name
	 * @return CreativeGroup|null
	 */
	public static function get(string $name): ?CreativeGroup {
		self::load();
		return self::$groups[$name] ?? null;
	}

	/**
	 * Sets a CreativeGroup instance in the internal list.
	 * @param CreativeGroup $group
	 * @return void
	 */
	public static function set(CreativeGroup $group): void {
		self::load();
		self::$groups[$group->getName()->getText()] = $group;
	}

	/**
	 * Returns all loaded CreativeGroup instances.
	 * @return CreativeGroup[]
	 */
	public static function all(): array {
		self::load();
		return self::$groups;
	}

	/**
	 * Registers the Item/Bloxk in the creative inventory based on the provided CreativeInventoryInfo.
	 * @param Item|Block $type The item/block to register
	 * @param CreativeInventoryInfo $creativeInfo The creative inventory information
	 */
	public static function registerCreativeInfo(
		Item|Block $type,
		CreativeInventoryInfo $creativeInfo
	): void {
		if(
			$creativeInfo->getCategory() === self::CATEGORY_ALL || 
			$creativeInfo->getCategory() === self::CATEGORY_COMMANDS
		){
			return;
		}
		$group = null;
		if($creativeInfo->getGroup() !== CreativeInventoryInfo::NONE){
			$group = CreativeInventoryInfo::get($creativeInfo->getGroup())
			?? new CreativeGroup(
				new Translatable($creativeInfo->getGroup()),
				$type instanceof Block ? $type->asItem() : $type
			);
		}
		$category = match($creativeInfo->getCategory()){
			CreativeInventoryInfo::CATEGORY_CONSTRUCTION => CreativeCategory::CONSTRUCTION,
			CreativeInventoryInfo::CATEGORY_ITEMS => CreativeCategory::ITEMS,
			CreativeInventoryInfo::CATEGORY_NATURE => CreativeCategory::NATURE,
			CreativeInventoryInfo::CATEGORY_EQUIPMENT => CreativeCategory::EQUIPMENT,
			default => throw new AssumptionFailedError("Unknown Creative Category: " . $creativeInfo->getCategory()),
		};
		CreativeInventory::getInstance()->add($type instanceof Block ? $type->asItem() : $type, $category, $group);
	}
}