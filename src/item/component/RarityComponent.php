<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class RarityComponent implements ItemComponent {

	public const COMMON = "common";
	public const UNCOMMON = "uncommon";
	public const RARE = "rare";
	public const EPIC = "epic";

	private string $rarity;

	public function __construct(string $rarity) {
		$this->rarity = $rarity;
	}

	public function getName(): string {
		return "minecraft:rarity";
	}

	public function getValue(): array {
		return [
			"value" => $this->rarity
		];
	}

	public function isProperty(): bool {
		return false;
	}
}