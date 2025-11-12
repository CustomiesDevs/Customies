<?php
declare(strict_types=1);

namespace customiesdevs\customies\Items\Components;

use customiesdevs\customies\Items\Components\ItemComponentBase;final class IconComponent implements ItemComponentBase {

	private string $texture;

	public function __construct(string $texture) {
		$this->texture = $texture;
	}

	public function getName(): string {
		return "minecraft:icon";
	}

	public function getValue(): array {
		return [
			"textures" => [
				"default" => $this->texture
			]
		];
	}

	public function isProperty(): bool {
		return true;
	}
}
