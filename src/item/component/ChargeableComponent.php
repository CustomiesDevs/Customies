<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class ChargeableComponent extends BasicComponent {

    /**
     * @param float $movementModifier
     */
    public function __construct(float $movementModifier) {
        parent::__construct("minecraft:chargeable", [
            "movement_modifier" => $movementModifier
        ], false);
    }
}