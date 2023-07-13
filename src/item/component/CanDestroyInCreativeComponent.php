<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class CanDestroyInCreativeComponent implements ItemComponent {

    private bool $canDestroyInCreative;

    public function __construct(bool $canDestroyInCreative = true) {
        $this->canDestroyInCreative = $canDestroyInCreative;
    }

    public function getName(): string {
        return "can_destroy_in_creative";
    }

    public function getValue(): bool {
        return $this->canDestroyInCreative;
    }

    public function isProperty(): bool {
        return true;
    }
}
