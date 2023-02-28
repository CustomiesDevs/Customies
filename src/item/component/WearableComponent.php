<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

use customiesdevs\customies\item\enum\Slot;

final class WearableComponent extends BasicComponent {

    /**
     * @param Slot $slot
     */
    public function __construct(Slot $slot) {
        parent::__construct("minecraft:wearable", [
            "slot" => $slot->value
        ], false);
    }
}