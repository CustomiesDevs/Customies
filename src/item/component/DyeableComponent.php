<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class DyeableComponent implements ItemComponent {

	/** 
	 * RGB color values as an array of three integers [R, G, B].
	 * @var int[]
	 */
	private array $rgb = [];

	/**
	 * Allows the item to be dyed by cauldron water. Once dyed, the item will display the `dyed` texture defined in the `minecraft:icon` component rather than `default`.
	 * @param string $hex Hex color code (e.g. "#175882")
	 * @throws \InvalidArgumentException If the hex code is invalid
	 */
	public function __construct(string $hex) {
		$this->rgb = self::hexToRgb($hex);
	}

	public function getName(): string {
		return 'minecraft:dyeable';
	}

	public function getValue(): array {
		return [
			"default_color" => $this->rgb
		];
	}

	public function getPropertyMapping(): ?array {
		return null;
	}

	/**
	 * Converts a hex color string to an RGB array.
	 *
	 * @param string $hex Hex color string (e.g. "#175882")
	 * @return int[] Array of RGB values [R, G, B]
	 * @throws \InvalidArgumentException If the hex code is invalid
	 */
	private static function hexToRgb(string $hex): array {
		$hex = ltrim($hex, '#');
		if(strlen($hex) !== 6) throw new \InvalidArgumentException("Invalid hex color: {$hex}");
		return [
			hexdec(substr($hex, 0, 2)),
			hexdec(substr($hex, 2, 2)),
			hexdec(substr($hex, 4, 2))
		];
	}
}