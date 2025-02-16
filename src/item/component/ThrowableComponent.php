<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class ThrowableComponent implements ItemComponent {

	private bool $doSwingAnimation;
	private float $launchPowerScale;
	private float $maxDrawDuration;
	private float $maxLaunchPower;
	private float $minDrawDuration;
	private bool $scalePowerByDrawDuration;

	/**
	 * Sets the throwable item component.
	 * @param bool $doSwingAnimation Determines whether the item should use the swing animation when thrown
	 * @param float $launchPowerScale The scale at which the power of the throw increases
	 * @param float $maxDrawDuration The maximum duration to draw a throwable item
	 * @param float $maxLaunchPower The maximum power to launch the throwable item
	 * @param float $minDrawDuration The minimum duration to draw a throwable item
	 * @param bool $scalePowerByDrawDuration Whether or not the power of the throw increases with duration charged
	 */
	public function __construct(bool $doSwingAnimation = false, float $launchPowerScale = 1.0, float $maxDrawDuration = 0.0, float $maxLaunchPower = 1.0, float $minDrawDuration = 0.0, bool $scalePowerByDrawDuration = false) {
		$this->doSwingAnimation = $doSwingAnimation;
		$this->launchPowerScale = $launchPowerScale;
		$this->maxDrawDuration = $maxDrawDuration;
		$this->maxLaunchPower = $maxLaunchPower;
		$this->minDrawDuration = $minDrawDuration;
		$this->scalePowerByDrawDuration = $scalePowerByDrawDuration;
	}

	public function getName(): string {
		return "minecraft:throwable";
	}

	public function getValue(): array {
		return [
			"do_swing_animation" => $this->doSwingAnimation,
			"launch_power_scale" => $this->launchPowerScale,
			"max_draw_duration" => $this->maxDrawDuration,
			"max_launch_power" => $this->maxLaunchPower,
			"min_draw_duration" => $this->minDrawDuration,
			"scale_power_by_draw_duration" => $this->scalePowerByDrawDuration
		];
	}

	public function isProperty(): bool {
		return false;
	}
}