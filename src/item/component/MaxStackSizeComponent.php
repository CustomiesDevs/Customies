<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class MaxStackSizeComponent implements ItemComponent {

	private int $maxStackSize;

	/**
	 * Determines how many of an item can be stacked together.
	 * @param int $maxStackSize Max Size, Default is set to `64`
	 */
	public function __construct(int $maxStackSize = 64) {
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