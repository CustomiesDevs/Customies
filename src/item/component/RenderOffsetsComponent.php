<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class RenderOffsetsComponent extends BasicComponent {

    /**
     * @param int $textureWidth
     * @param int $textureHeight
     * @param bool $handEquipped
     */
    public function __construct(int $textureWidth, int $textureHeight, bool $handEquipped = false) {

        $horizontal = ($handEquipped ? 0.075 : 0.1) / ($textureWidth / 16);
        $vertical = ($handEquipped ? 0.125 : 0.1) / ($textureHeight / 16);
        $scale = [$horizontal, $vertical, $horizontal];

        $perspectives = [
            "first_person" => [
                "scale" => $scale,
            ],
            "third_person" => [
                "scale" => $scale
            ]
        ];

        parent::__construct("minecraft:render_offsets", [
            "main_hand" => $perspectives,
            "off_hand" => $perspectives
        ], false);
    }
}