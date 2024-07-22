<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class StackedByDataComponent implements ItemComponent {

	private bool $stackedByData;

	public function __construct(bool $stackedByData) {
		$this->stackedByData = $stackedByData;
	}

	public function getName(): string {
		return "minecraft:stacked_by_data";
	}

	public function getValue(): array {
		return ["value" => $this->stackedByData];
	}

	public function isProperty(): bool {
		return false;
	}
}