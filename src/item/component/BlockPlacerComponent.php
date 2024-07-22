<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class BlockPlacerComponent implements ItemComponent {

    private string $blockIdentifier;
    private array $useOn;

    public function __construct(string $blockIdentifier, array $useOn) {
        $this->blockIdentifier = $blockIdentifier;
        $this->useOn = $useOn;
    }

    public function getName(): string {
        return "minecraft:block_placer";
    }

    public function getValue(): array {
        return [
            "block" => $this->blockIdentifier,
            "use_on" => $this->useOn
        ];
    }

    public function isProperty(): bool {
        return false;
    }
}
