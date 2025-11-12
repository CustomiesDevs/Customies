<?php
declare(strict_types=1);

namespace customiesdevs\customies\Items\Components;


use customiesdevs\customies\Items\Components\ItemComponentBase;final class BlockPlacerComponent implements ItemComponentBase {

    private string $blockIdentifier;
    private bool $useBlockDescription;

    public function __construct(string $blockIdentifier, bool $useBlockDescription = false) {
        $this->blockIdentifier = $blockIdentifier;
        $this->useBlockDescription = $useBlockDescription;
    }

    public function getName(): string {
        return "minecraft:block_placer";
    }

    public function getValue(): array {
        return [
            "block" => $this->blockIdentifier,
            "use_block_description" => $this->useBlockDescription
        ];
    }

    public function isProperty(): bool {
        return false;
    }
}
