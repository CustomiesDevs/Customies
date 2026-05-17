<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class StorageWeightModifierComponent implements ItemComponent {

	private int $weightInStorageItem;

	/**
	 * Specifies the maximum weight limit that a storage item can hold.
	 * @param int $weightInStorageItem The weight of this item when inside another Storage Item. Default is 4. 0 means item is not allowed in another Storage Item.
	 */
	public function __construct(int $weightInStorageItem = 4) {
		$this->weightInStorageItem = $weightInStorageItem;
	}

	public function getName(): string {
		return 'minecraft:storage_weight_modifier';
	}

	public function getValue(): array {
		return [
			"weight_in_storage_item" => $this->weightInStorageItem
		];
	}

	public function getPropertyMapping(): ?array {
		return null;
	}
}