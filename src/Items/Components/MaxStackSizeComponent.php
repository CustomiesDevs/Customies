<?php
declare(strict_types=1);

namespace customiesdevs\customies\Items\Components;

use customiesdevs\customies\Items\Components\ItemComponentBase;final class MaxStackSizeComponent implements ItemComponentBase {

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