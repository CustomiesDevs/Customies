<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class EnchantableComponent implements ItemComponent {

	private string $slot;
	private int $value;

	public function __construct(string $slot, int $value) {
		$this->slot = $slot;
		$this->value = $value;
	}

	public function getName(): string {
		return "minecraft:enchantable";
	}

	public function getValue(): array {
		return [
			"slot" => $this->slot,
			"value" => $this->value
		];
	}

	public function isProperty(): bool {
		return false;
	}
}