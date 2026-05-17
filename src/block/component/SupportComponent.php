<?php

namespace customiesdevs\customies\block\component;

final class SupportComponent implements BlockComponent {

	public const TYPE_FENCE = "fence";
	public const TYPE_STAIRS = "stair";

	private string $shape;

	/**
	 * @param string $shape Support shape (e.g. "stair")
	 */
	public function __construct(string $shape = self::TYPE_STAIRS) {
		$this->shape = $shape;
	}

	public function getName(): string {
		return 'minecraft:support';
	}

	public function getValue(): array {
		return [
			"shape" => $this->shape
		];
	}
}