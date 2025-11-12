<?php
declare(strict_types=1);

namespace customiesdevs\customies\Items\Components;

use customiesdevs\customies\Items\Components\ItemComponentBase;use pocketmine\block\Block;
use pocketmine\world\format\io\GlobalBlockStateHandlers;
use function array_map;
use function implode;

final class DiggerComponent implements ItemComponentBase {

    private array $destroySpeeds;
    private int $efficiency;

    public function __construct(int $efficiency)
    {
        $this->efficiency = $efficiency;
    }

    public function getName(): string {
        return "minecraft:digger";
    }

    public function getValue(): array {
        return [
            "use_efficiency" => $this->efficiency,
            "destroy_speeds" => $this->destroySpeeds
        ];
    }

    public function isProperty(): bool {
        return false;
    }

    public function withBlocks(int $speed, Block ...$blocks): DiggerComponent {
        foreach($blocks as $block){
            $this->destroySpeeds[] = [
                "block" => GlobalBlockStateHandlers::getSerializer()->serialize($block->getStateId())->getName(),
                "speed" => $speed
            ];
        }
        return $this;
    }

    public function withTags(int $speed, string ...$tags): DiggerComponent {
        $this->destroySpeeds[] = [
            "block" => [
                "tags" => "query.any_tag(" . implode(",", array_map(fn($tag) => "'" . $tag . "'", $tags)) . ")"
            ],
            "speed" => $speed
        ];
        return $this;
    }
}