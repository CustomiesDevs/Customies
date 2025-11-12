<?php

declare(strict_types=1);

namespace customiesdevs\customies\Items\Components;

use customiesdevs\customies\Items\Components\ItemComponentBase;final class HoverTextColorComponent implements ItemComponentBase
{
    private string $value;

    public function __construct(string $value = "§s") {
        $this->value = $value;
    }

    public function getName(): string {
        return "hover_text_color";
    }

    public function getValue(): string {
        return $this->value;
    }

    public function isProperty(): bool {
        return true;
    }
}