<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class LiquidClippedComponent implements ItemComponent {

	private bool $liquidClipped;

	/**
	 * Determines whether an item interacts with liquid blocks on use.
	 * @param bool $liquidClipped If the item interacts with liquid blocks on use
	 */
	public function __construct(bool $liquidClipped = true) {
		$this->liquidClipped = $liquidClipped;
	}

	public function getName(): string {
		return 'minecraft:liquid_clipped';
	}

	public function getValue(): array {
		return [
			"value" => $this->liquidClipped
		];
	}

	public function getPropertyMapping(): ?array {
		return ['liquid_clipped' => $this->liquidClipped];
	}
}