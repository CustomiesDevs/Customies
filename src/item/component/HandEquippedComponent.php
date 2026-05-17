<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class HandEquippedComponent implements ItemComponent {

	private bool $handEquipped;

	/**
	 * Determines if an item is rendered like a tool while in-hand.
	 * @param bool $handEquipped Default is set to `true`
	 */
	public function __construct(bool $handEquipped = true) {
		$this->handEquipped = $handEquipped;
	}

	public function getName(): string {
		return 'minecraft:hand_equipped';
	}

	public function getValue(): array {
		return [
			"value" => $this->handEquipped
		];
	}

	public function getPropertyMapping(): ?array {
		return ['hand_equipped' => $this->handEquipped];
	}
}