<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class IconComponent extends BasicComponent {

	public function __construct(string $texture) {
		parent::__construct("minecraft:icon", [
			"texture" => $texture
		], true);
	}
}