<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class AllowOffHandComponent extends BasicComponent
{

	/**
	 * @param bool $offHand
	 */
	public function __construct(bool $offHand = true)
	{
		parent::__construct("allow_off_hand", $offHand, true);
	}
}