<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;


final class RenderOffsetsComponent implements ItemComponent { //thanks to https://github.com/MedicalJewel105/bedrock-render-offsets-generator/blob/main/main.py

	private int $textureSize;

	public function __construct(int $textureSize) {
		$this->textureSize = $textureSize;
	}

	public function getName(): string {
		return "minecraft:render_offsets";
	}

	public function getValue(): array {
	        $textureSize = $this->textureSize;
	        $mainHandFirst = round(0.039 * 16 / $textureSize, 8);
	        $offHandFirst = round(0.065 * 16 / $textureSize, 8);
	        $mainHandThird = $offHandThird = round(0.0965 * 16 / $textureSize, 8);

		return [
			"main_hand" => [
				"first_person" => [
					"scale" => [$mainHandFirst, $mainHandFirst, $mainHandFirst],
				],
				"third_person" => [
					"scale" => [$mainHandThird, $mainHandThird, $mainHandThird]
				]
			],
			"off_hand" => [
				"first_person" => [
					"scale" => [$offHandFirst, $offHandFirst, $offHandFirst],
				],
				"third_person" => [
					"scale" => [$offHandThird, $offHandThird, $offHandThird]
				]
			]
		];
	}

	public function isProperty(): bool {
		return false;
	}
}
