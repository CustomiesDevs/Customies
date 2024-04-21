<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class UseModifiersComponent implements ItemComponent {

    public function __construct(private float $movementModifier,private float $useDuration) {}

    public function getName(): string {
        return "minecraft:use_modifiers";
    }

    public function getValue(): array {
        return [
            "movement_modifier" => $this->movementModifier,
            "use_duration" => $this->useDuration
        ];
    }

    public function isProperty(): bool {
        return false;
    }
}
