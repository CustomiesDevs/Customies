<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class GlintComponent implements ItemComponent {

	private bool $glint;

	/**
	 * Determines whether the item has the enchanted glint render effect on it.
	 * @param bool $glint Default is set to `true`
	 */
	public function __construct(bool $glint = true) {
		$this->glint = $glint;
	}

	public function getName(): string {
		return 'minecraft:glint';
	}

	public function getValue(): array {
		return [
			"value" => $this->glint
		];
	}

	public function getPropertyMapping(): ?array {
		return ['foil' => $this->glint];
	}
}