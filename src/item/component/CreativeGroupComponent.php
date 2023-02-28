<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

use customiesdevs\customies\item\CreativeInventoryInfo;

final class CreativeGroupComponent extends BasicComponent {

	public function __construct(CreativeInventoryInfo $creativeInfo) {
        parent::__construct("creative_group", $creativeInfo->getGroup()->value, true);
	}
}