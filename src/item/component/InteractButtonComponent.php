<?php

declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class InteractButtonComponent implements ItemComponent{

    private bool $value;

    public function __construct(bool $value = true){
        $this->value = $value;
    }

    public function getName() : string{
        return "minecraft:interact_button";
    }

    public function getValue() : bool{
        return $this->value;
    }

    public function isProperty() : bool{
        return true;
    }

}