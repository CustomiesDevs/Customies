<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

use pocketmine\item\Item;

final class StorageItemComponent implements ItemComponent {

	private bool $allowNestedStorageItems;
	private array $allowedItems = [];
	private array $bannedItems = [];
	private int $maxSlots;

	/**
	 * Enables an item to store data of the dynamic container associated with it. 
	 * A dynamic container is a container for storing items that is linked to an item instead of a block or an entity.
	 * @param bool $allowNestedStorageItems Determines whether another Storage Item is allowed inside of this item. Default is true.
	 * @param int $maxSlots The maximum allowed weight of the sum of all contained items. Maximum is 64. Default is 64. Value must be >= 0.
	 */
	public function __construct(
		bool $allowNestedStorageItems = true,
		int $maxSlots = 64
	) {
		$this->allowNestedStorageItems = $allowNestedStorageItems;
		$this->maxSlots = $maxSlots;
	}

	public function getName(): string {
		return 'minecraft:storage_item';
	}

	public function getValue(): array {
		return [
			"allow_nested_storage_items" => $this->allowNestedStorageItems,
			"allowed_items" => $this->allowedItems,
			"banned_items" => $this->bannedItems,
			"max_slots" => $this->maxSlots
		];
	}

	public function getPropertyMapping(): ?array {
		return null;
	}

	/**
	 * List of items that are exclusively allowed in this Storage Item.
	 * If empty, all items are allowed.
	 * @param Item|Item[] $items
	 */
	public function allowItem(Item|array $items): self {
		foreach($this->normalizeItems($items) as $name){
			if(!$this->containsItem($this->allowedItems, $name)){
				$this->allowedItems[] = ["name" => $name];
			}
		}
		return $this;
	}

	/**
	 * List of items that are NOT allowed in this Storage Item.
	 * @param Item|Item[] $items
	 */
	public function banItem(Item|array $items): self {
		foreach($this->normalizeItems($items) as $name){
			if(!$this->containsItem($this->bannedItems, $name)){
				$this->bannedItems[] = ["name" => $name];
			}
		}
		return $this;
	}

	/**
	 * @return string[]
	 */
	private function normalizeItems(Item|array $items): array {
		$items = is_array($items) ? $items : [$items];
		$names = [];
		foreach($items as $item){
			if(!$item instanceof Item) continue;
			$names[] = $item->nbtSerialize()->getString("Name", "unknown");
		}
		return array_unique($names);
	}

	private function containsItem(array $list, string $name): bool {
		foreach($list as $entry){
			if(($entry["name"] ?? null) === $name){
				return true;
			}
		}
		return false;
	}
}