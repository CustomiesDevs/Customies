<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class UseDurationComponent extends BasicComponent {

    /**
     * @param int $duration
     */
	public function __construct(int $duration) {
		$this->duration = $duration;
        parent::__construct("use_duration", $duration, true);
	}
}