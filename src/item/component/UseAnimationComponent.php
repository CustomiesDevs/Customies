<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;


final class UseAnimationComponent extends BasicComponent
{

	public const ANIMATION_EAT = 1;
	public const ANIMATION_DRINK = 2;

	/**
	 * @param int $animation
	 */
	public function __construct(int $animation)
	{
		parent::__construct("use_animation", $animation, true);
	}
}