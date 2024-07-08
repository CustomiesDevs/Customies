<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class RenderOffsetsComponent implements ItemComponent {

	private int $textureWidth;
	private int $textureHeight;
	private bool $handEquipped;

	public function __construct(int $textureWidth, int $textureHeight, bool $handEquipped = false) {
		$this->textureWidth = $textureWidth;
		$this->textureHeight = $textureHeight;
		$this->handEquipped = $handEquipped;
	}

	public function getName(): string {
		return "minecraft:render_offsets";
	}

	public function getValue(): array {
		$horizontal = ($this->handEquipped ? 0.075 : 0.1) / ($this->textureWidth / 16);
		$vertical = ($this->handEquipped ? 0.125 : 0.1) / ($this->textureHeight / 16);
		$perspectives = [
			"first_person" => [
				"scale" => [$horizontal / 2, $vertical / 2, $horizontal / 2],
			],
			"third_person" => [
				"scale" => [$horizontal, $vertical, $horizontal]
			]
		];
		return [
			"main_hand" => $perspectives,
			"off_hand" => $perspectives
		];
	}

	public function isProperty(): bool {
		return false;
	}
}