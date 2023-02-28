<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class DurabilityComponent extends BasicComponent {

    /**
     * @param int $maxDurability
     */
    public function __construct(int $maxDurability) {
        parent::__construct("minecraft:durability", [
            "max_durability" => $maxDurability
        ], false);
    }
}