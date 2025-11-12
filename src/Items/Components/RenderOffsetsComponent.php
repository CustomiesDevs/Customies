<?php
declare(strict_types=1);

namespace customiesdevs\customies\Items\Components;


use customiesdevs\customies\Items\Components\ItemComponentBase;final class RenderOffsetsComponent implements ItemComponentBase { //thanks to https://github.com/MedicalJewel105/bedrock-render-offsets-generator/blob/main/main.py

    private int $textureSize;

    public function __construct(int $textureSize) {
        $this->textureSize = $textureSize;
    }

    public function getName(): string {
        return "minecraft:render_offsets";
    }

    public function getValue(): array {
        $textureSize = $this->textureSize;
        $mainHand_fp = round(0.039 * 16 / $textureSize, 8);
        $offhand_fp = round(0.065 * 16 / $textureSize, 8);
        $mainHand_tp = $offhand_tp = round(0.0965 * 16 / $textureSize, 8);

        return [
            "main_hand" => [
                "first_person" => [
                    "scale" => [$mainHand_fp, $mainHand_fp, $mainHand_fp],
                ],
                "third_person" => [
                    "scale" => [$mainHand_tp, $mainHand_tp, $mainHand_tp]
                ]
            ],
            "off_hand" => [
                "first_person" => [
                    "scale" => [$offhand_fp, $offhand_fp, $offhand_fp],
                ],
                "third_person" => [
                    "scale" => [$offhand_tp, $offhand_tp, $offhand_tp]
                ]
            ]
        ];
    }

    public function isProperty(): bool {
        return false;
    }
}