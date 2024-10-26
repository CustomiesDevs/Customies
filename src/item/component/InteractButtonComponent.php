<?php

declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class InteractButtonComponent implements ItemComponent{


    public function __construct(
        private string $text
    ){
    }

    public function getName() : string{
        return "minecraft:interact_button";
    }

    public function getValue() : array{
        return [
            'interact_text' => $this->text,
            'requires_interact' => true
        ];
    }

    public function isProperty() : bool{
        return false;
    }

}