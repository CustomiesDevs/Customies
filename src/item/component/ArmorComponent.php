<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

use customiesdevs\customies\item\enum\ArmorComponentTextureType;

final class ArmorComponent implements ItemComponent {

	private int $protection;
	private string $textureType;

	public function __construct(int $protection, ArmorComponentTextureType $textureType = ArmorComponentTextureType::NONE) {
		$this->protection = $protection;
		$this->textureType = $textureType->value;
	}

	public function getName(): string {
		return "minecraft:armor";
	}

	public function getValue(): array {
		return [
			"protection" => $this->protection,
			"texture_type" => $this->textureType
		];
	}

	public function isProperty(): bool {
		return false;
	}
}