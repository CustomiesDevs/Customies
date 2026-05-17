<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

use pocketmine\nbt\tag\ByteTag;

final class CompostableComponent implements ItemComponent {

	private int $compostingChance;

	/**
	 * Specifies that an item is compostable and provides the chance of creating a composting layer in the composter.
	 * @param int $compostingChance The chance of this item to create a layer upon composting with the composter.
	 * @throws \InvalidArgumentException if the composting chance is not between 1 and 100.
	 */
	public function __construct(int $compostingChance) {
		if($compostingChance < 1 || $compostingChance > 100) {
			throw new \InvalidArgumentException("Composting chance must be between 1 and 100, $compostingChance given");
		}
		$this->compostingChance = $compostingChance;
	}

	public function getName(): string {
		return 'minecraft:compostable';
	}

	public function getValue(): array {
		return [
			"composting_chance" => new ByteTag($this->compostingChance)
		];
	}

	public function getPropertyMapping(): ?array {
		return null;
	}
}