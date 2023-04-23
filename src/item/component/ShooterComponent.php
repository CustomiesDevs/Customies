<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class ShooterComponent implements ItemComponent {


  private bool $chargeOnDraw = false;//Sets if the item is charged when drawn
  private float $launchPowerScale = 1.0;//Scale used for the launch power
  private float $maxDrawDuration = 0;//How long can it be drawn before it will release automatically
  private float $maxLaunchPower = 1.0;//Launch power
  private bool $scalePowerByDuration = false;//Scale the power by draw duration? When true, the longer you hold, the more power it will have when released.
  private string $ammunition = "";//Sets the entity that is used as ammunition

  public function getName(): string {
    return "minecraft:shooter";
  }

  public function getValue(): array {
    return [
      "charge_on_draw" => $this->chargeOnDraw,
      "launch_power_scale" => $this->launchPowerScale,
      "max_draw_duration" => $this->maxDrawDuration,
      "max_launch_power" => $this->maxLaunchPower,
      "scale_power_by_draw_duration" => $this->scalePowerByDuration,
      "ammunition" => $this->ammunition
    ];
  }

  public function isProperty(): bool {
    return false;
  }
}