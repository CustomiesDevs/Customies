<?php
declare(strict_types=1);

namespace customiesdevs\customies\Items\Components;

use customiesdevs\customies\Items\Components\ItemComponentBase;final class DyeableComponent implements ItemComponentBase {

    private string $hex;

    public function __construct(string $hex) {
        $this->hex = $hex;
    }

    public function getName(): string {
        return "minecraft:dyeable";
    }

    public function getValue(): array {
        return [
            "default_color" => $this->hex
        ];
    }

    public function isProperty(): bool {
        return false;
    }
}