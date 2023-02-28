<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

enum ArmorComponentTextureType: string {
    case CHAIN = "chain";
    case DIAMOND = "diamond";
    case ELYTRA = "elytra";
    case GOLD = "gold";
    case IRON = "iron";
    case LEATHER = "leather";
    case NETHERITE = "netherite";
    case NONE = "none";
    case TURTLE = "turtle";
}

final class ArmorComponent implements ItemComponent {

	private int $protection;
	private ArmorComponentTextureType $textureType;

	public function __construct(int $protection, ArmorComponentTextureType $textureType = ArmorComponentTextureType::NONE) {
		$this->protection = $protection;
		$this->textureType = $textureType;
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