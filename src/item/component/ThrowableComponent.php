<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class ThrowableComponent extends BasicComponent {

    /**
     * @param bool $doSwingAnimation
     */
    public function __construct(bool $doSwingAnimation) {
        parent::__construct("minecraft:throwable", [
            "do_swing_animation" => $doSwingAnimation
        ], false);
	}
}