<?php
declare(strict_types=1);

namespace customiesdevs\customies\Items\Components;

use customiesdevs\customies\Items\Components\ItemComponentBase;final class StorageItemComponent implements ItemComponentBase {

	private int $maxslots;

    private bool $ansi;

    private array $banned_items;

	public function __construct(int $maxslots, bool $ansi, array $banned_items) {
		$this->maxslots = $maxslots;
        $this->ansi = $ansi;
        $this->banned_items = $banned_items;
	}

	public function getName(): string {
		return "minecraft:storage_item"; //common, uncommon, rare, epic
	}


	public function getValue(): array {
		return [
            "value" => [
                "max_slots" => $this->maxslots,
                "allow_nested_storage_items"=> $this->ansi,
                "banned_items"=> $this->banned_items
            ]
        ];
	}

	public function isProperty(): bool {
		return true;
	}
}