<?php

namespace customiesdevs\customies\Items\Components;

use customiesdevs\customies\Items\Components\ItemComponentBase;final class FrameCountComponent implements ItemComponentBase {

    public int $framecount = 0;

    public function __construct(int $framecount)
    {
        $this->framecount = $framecount;
    }

    public function getName(): string
    {
        return "minecraft:frame_count";
    }

    public function getValue(): int
    {
        return $this->framecount;
    }

    public function isProperty(): bool
    {
        return true;
    }
}