<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class EnchantableComponent implements ItemComponent {

    private string $slot;



    public function __construct(string $slot) {
        $this->slot = $slot;
    }

    public function getName(): string {
        return "minecraft:enchantable";
    }

    public function getValue(): array{
        return [
            'slot' => 'sword',
        ];
    }

    public function isProperty(): bool {
        return false;
    }
}