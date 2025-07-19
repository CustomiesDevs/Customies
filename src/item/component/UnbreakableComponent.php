<?php

namespace customiesdevs\customies\item\component;

final class UnbreakableComponent implements ItemComponent
{

    private bool $unbreakable;

    /**
     * Allows the item to be unbreakable, meaning it will not lose durability when used.
     * @param bool $unbreakable If true, the item will not lose durability when used. Defaults to true.
     */
    public function __construct(bool $unbreakable = true){
        $this->unbreakable = $unbreakable;
    }

    public function getName(): string {
        return "minecraft:unbreakable";
    }

    public function getValue(): bool {
        return $this->unbreakable;
    }

    public function isProperty(): bool {
        return true;
    }

}