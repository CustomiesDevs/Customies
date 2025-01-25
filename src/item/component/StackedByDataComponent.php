<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class StackedByDataComponent implements ItemComponent {

	private bool $stackedByData;

	/**
	 * Determines if the same item with different aux values can stack. 
	 * Additionally, this component defines whether the item actors can merge while floating in the world.
	 * @param bool $stackedByData Should item stack, Default is set to `true`
	 */
	public function __construct(bool $stackedByData = true) {
		$this->stackedByData = $stackedByData;
	}

	public function getName(): string {
		return "stacked_by_data";
	}

	public function getValue(): bool {
		return $this->stackedByData;
	}

	public function isProperty(): bool {
		return true;
	}
}