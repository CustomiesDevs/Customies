<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class MaxStackSizeComponent implements ItemComponent {

	private int $maxStackSize;

	public function __construct(int $maxStackSize) {
		$this->maxStackSize = $maxStackSize;
	}

	public function getName(): string {
		return "max_stack_size";
	}

	public function getValue(): int {
		return $this->maxStackSize;
	}

	public function isProperty(): bool {
		return true;
	}
}