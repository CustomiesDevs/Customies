<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class HoverTextColorComponent implements ItemComponent {

	private string $hoverTextColor;

	/**
	 * Determines the color of the item name when hovering over it.
	 * @param string $hoverTextColor Specifies the color of the item's hover text
	 * @link [List of Color Code](https://minecraft.wiki/w/Formatting_codes#Color_codes)
	 */
	public function __construct(string $hoverTextColor) {
		$this->hoverTextColor = $hoverTextColor;
	}

	public function getName(): string {
		return "hover_text_color";
	}

	public function getValue(): string {
		return $this->hoverTextColor;
	}

	public function isProperty(): bool {
		return true;
	}
}