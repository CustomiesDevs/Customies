<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class FuelComponent extends BasicComponent {

    /**
     * @param float $duration
     */
    public function __construct(float $duration) {
        parent::__construct("minecraft:fuel", [
            "duration" => $duration
        ], false);
	}
}