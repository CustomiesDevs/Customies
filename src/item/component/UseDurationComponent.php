<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class UseDurationComponent implements ItemComponent {

	private int $duration;

	/**
	 * How long the item takes to use in seconds.
	 * @param int $duration Default is set to `20` (`1` means `20` seconds) so (`1.6` means `32` seconds)
	 */
	public function __construct(int $duration = 20) {
		$this->duration = $duration;
	}

	public function getName(): string {
		return "use_duration";
	}

	public function getValue(): int {
		return $this->duration;
	}

	public function isProperty(): bool {
		return true;
	}
}