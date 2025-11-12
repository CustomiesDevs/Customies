<?php
declare(strict_types=1);

namespace customiesdevs\customies\Items\Components;

use customiesdevs\customies\Items\Components\ItemComponentBase;final class FoilComponent implements ItemComponentBase {

	private bool $foil;

	public function __construct(bool $foil = true) {
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