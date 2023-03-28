<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class DurabilityComponent extends BasicComponent {

	public function __construct(int $maxDurability) {
		parent::__construct("minecraft:durability", [
			"max_durability" => $maxDurability
		], false);
	}
}