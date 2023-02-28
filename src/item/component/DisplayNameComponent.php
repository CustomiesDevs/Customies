<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class DisplayNameComponent extends BasicComponent {

    /**
     * @param string $name
     */
    public function __construct(string $name) {
        parent::__construct("minecraft:display_name", [
            "value" => $name
        ], false);
    }
}