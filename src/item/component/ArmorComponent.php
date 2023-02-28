<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

use customiesdevs\customies\item\enum\ArmorComponentTextureType;

final class ArmorComponent extends BasicComponent {

    /**
     * @param int $protection
     * @param ArmorComponentTextureType $textureType
     */
    public function __construct(int $protection, ArmorComponentTextureType $textureType = ArmorComponentTextureType::NONE) {
        parent::__construct("minecraft:armor", [
            "protection" => $protection,
            "texture_type" => $textureType->value
        ], false);
	}
}