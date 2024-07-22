<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class InteractButtonComponent implements ItemComponent {

	private bool | string $interactButton;

	public function __construct(bool | string $interactButton) {
		$this->interactButton = $interactButton;
	}

	public function getName(): string {
		return "minecraft:interact_button";
	}

	public function getValue(): bool | string {
		return $this->interactButton;
	}

	public function isProperty(): bool {
		return false;
	}
}
