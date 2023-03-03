<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

use customiesdevs\customies\item\CreativeInventoryInfo;

final class CreativeCategoryComponent extends BasicComponent
{

	/**
	 * @param CreativeInventoryInfo $creativeInventoryInfo
	 */
	public function __construct(CreativeInventoryInfo $creativeInventoryInfo)
	{
		parent::__construct("creative_category", $creativeInventoryInfo->getNumericCategory(), true);
	}
}