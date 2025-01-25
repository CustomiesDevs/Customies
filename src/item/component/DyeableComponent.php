<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class DyeableComponent implements ItemComponent {

	private string $hex;

	/**
	 * Allows the item to be dyed by cauldron water. Once dyed, the item will display the `dyed` texture defined in the `minecraft:icon` component rather than `default`.
	 * @param string $hex The hex color code (e.g "#47ff5a")
	 */
	public function __construct(string $hex) {
		$this->hex = $hex;
	}

	public function getName(): string {
		return "minecraft:dyeable";
	}

	public function getValue(): array {
		return [
			"default_color" => $this->hex
		];
	}

	public function isProperty(): bool {
		return false;
	}
}