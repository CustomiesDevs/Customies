<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class MaxStackSizeComponent extends BasicComponent {

	public function __construct(int $maxStackSize) {
		parent::__construct("max_stack_size", $maxStackSize, true);
	}
}