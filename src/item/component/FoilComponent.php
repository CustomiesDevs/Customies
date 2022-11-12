<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class FoilComponent implements ItemComponent {

	private bool $foil;

	public function __construct(bool $foil) {
		$this->foil = $foil;
	}

	public function getName(): string {
		return "foil";
	}

	public function getValue(): bool {
		return $this->foil;
	}

	public function isProperty(): bool {
		return true;
	}
}