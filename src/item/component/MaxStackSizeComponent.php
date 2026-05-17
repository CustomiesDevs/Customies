<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

use pocketmine\nbt\tag\ByteTag;

final class MaxStackSizeComponent implements ItemComponent {

	private int $maxStackSize;

	/**
	 * Determines how many of an item can be stacked together.
	 * @param int $maxStackSize Max Size, Default is set to `64`
	 */
	public function __construct(int $maxStackSize = 64) {
		if($maxStackSize < 1 || $maxStackSize > 64) {
			throw new \InvalidArgumentException("Max stack size must be between 1 and 64, $maxStackSize given.");
		}
		$this->maxStackSize = $maxStackSize;
	}

	public function getName(): string {
		return 'minecraft:max_stack_size';
	}

	public function getValue(): array {
		return [
			"value" => new ByteTag($this->maxStackSize)
		];
	}

	public function getPropertyMapping(): ?array {
		return ['max_stack_size' => (int) $this->maxStackSize];
	}
}