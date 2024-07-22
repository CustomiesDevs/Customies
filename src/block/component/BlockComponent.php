<?php

namespace customiesdevs\customies\block\component;

interface BlockComponent {
    public function getName(): string;
    public function getValue(): mixed;
}