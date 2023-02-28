<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class FoilComponent extends BasicComponent {

    /**
     * @param bool $foil
     */
    public function __construct(bool $foil = true) {
        parent::__construct("foil", $foil, true);
    }
}