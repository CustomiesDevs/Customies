<?php
declare(strict_types=1);

namespace customiesdevs\customies\block\component;

interface BlockComponent {

	public function getName(): string;

	public function getValue(): mixed;

}