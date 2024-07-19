<?php

declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class InteractButtonComponent implements ItemComponent{

    private string $value;

    public function __construct(string $value = "true"){
        $this->value = $value;
    }

    public function getName() : string{
        return "minecraft:interact_button";
    }

    public function getValue() : string{
        return $this->value;
    }

    public function isProperty() : bool{
        return false;
    }

}