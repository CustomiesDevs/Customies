<?php

namespace customiesdevs\customies\block\component;

use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;

class CraftingTableComponent implements BlockComponent {

	private string $tableName;
	private array $craftingTags;

	public function __construct(string $tableName, array $craftingTags) {
		$this->tableName = $tableName;
		$this->craftingTags = $craftingTags;
	}

	public function getName(): string {
		return "minecraft:crafting_table";
	}

	public function getValue(): CompoundTag {
		return CompoundTag::create()
			->setString("table_name", $this->tableName)
			->setTag("crafting_tags", new ListTag($this->craftingTags));
	}
}