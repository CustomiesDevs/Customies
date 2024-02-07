<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

use pocketmine\nbt\tag\CompoundTag;

final class IconComponent implements ItemComponent {

	private string $texture;

	public function __construct(string $texture) {
		$this->texture = $texture;
	}

	public function getName(): string {
		return "minecraft:icon";
	}

	public function getValue(): array {
		return CompoundTag::create()
			->setTag("textures",
				CompoundTag::create()
					->setString("default", $this->texture)
			);
	}

	public function isProperty(): bool {
		return false;
	}
}