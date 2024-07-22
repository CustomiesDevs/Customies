<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class HoverTextColorComponent implements ItemComponent {

	private string $hoverTextColor;

	public function __construct(string $hoverTextColor) {
		$this->hoverTextColor = $hoverTextColor;
	}

	public function getName(): string {
		return "minecraft:hover_text_color";
	}

	public function getValue(): string {
		return $this->hoverTextColor;
	}

	public function isProperty(): bool {
		return false;
	}
}