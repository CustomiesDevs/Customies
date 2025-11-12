<?php
declare(strict_types=1);

namespace customiesdevs\customies\Items\Components;

use customiesdevs\customies\Items\Components\ItemComponentBase;final class LiquidClippedComponent implements ItemComponentBase {

    private bool $liquidClipped;

    /**
     * Determines whether an item interacts with liquid blocks on use.
     * @param bool $liquidClipped If the item interacts with liquid blocks on use
     */
    public function __construct(bool $liquidClipped = true) {
        $this->liquidClipped = $liquidClipped;
    }

    public function getName(): string {
        return "liquid_clipped";
    }

    public function getValue(): bool {
        return $this->liquidClipped;
    }

    public function isProperty(): bool {
        return true;
    }
}