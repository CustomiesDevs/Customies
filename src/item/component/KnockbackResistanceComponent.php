<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class KnockbackResistanceComponent extends BasicComponent {

    /**
     * @param float $protection
     */
    public function __construct(float $protection) {
        parent::__construct("minecraft:knockback_resistance", [
            "protection" => $protection
        ], false);
    }
}