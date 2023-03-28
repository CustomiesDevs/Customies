<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class FoodComponent extends BasicComponent {

	public function __construct(bool $canAlwaysEat = false) {
		parent::__construct("minecraft:food", [
			"can_always_eat" => $canAlwaysEat
		], false);
	}
}