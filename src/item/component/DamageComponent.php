<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class DamageComponent implements ItemComponent {

    private int $damage;

    public function __construct(int $damage) {
        $this->damage = $damage;
    }

    public function getName(): string {
        return "minecraft:damage";
    }

    public function getValue(): array {
        return [
            "damage" => $this->damage
        ];
    }

    public function isProperty(): bool {
        return false;
    }
}