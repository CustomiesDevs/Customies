<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class ShooterComponent implements ItemComponent {

  //TODO Ammunition
  private bool $chargeOnDraw;
  private float $launchPowerScale;
  private float $maxDrawDuration;
  private float $maxLaunchPower;
  private bool $scalePowerByDuration;

  public function __construct(bool $chargeOnDraw = false, float $launchPowerScale = 1.0, float $maxDrawDuration = 0, float $maxLaunchPower = 1.0, bool $scalePowerByDuration = false) {
    $this->chargeOnDraw = $chargeOnDraw;
    $this->launchPowerScale = $launchPowerScale;
    $this->maxDrawDuration = $maxDrawDuration;
    $this->maxLaunchPower = $maxLaunchPower;
    $this->scalePowerByDuration = $scalePowerByDuration;
  }

  public function getName(): string {
    return "minecraft:shooter";
  }

  public function getValue(): array {
    return [
      "charge_on_draw" => $this->chargeOnDraw,
      "launch_power_scale" => $this->launchPowerScale,
      "max_draw_duration" => $this->maxDrawDuration,
      "max_launch_power" => $this->maxLaunchPower,
      "scale_power_by_draw_duration" => $this->scalePowerByDuration
    ];
  }

  public function isProperty(): bool {
    return false;
  }
}