<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class HandEquippedComponent extends BasicComponent {

    public function __construct(bool $handEquipped = true) {
        parent::__construct("hand_equipped", $handEquipped, true);
    }
}