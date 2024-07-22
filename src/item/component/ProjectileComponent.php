<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class ProjectileComponent implements ItemComponent {

	private float $minimumCriticalPower;
	private string $projectileEntity;

	public function __construct(float $minimumCriticalPower, string $projectileEntity) {
		$this->minimumCriticalPower = $minimumCriticalPower;
		$this->projectileEntity = $projectileEntity;
	}

	public function getName(): string {
		return "minecraft:projectile";
	}

	public function getValue(): array {
		return [
			"minimum_critical_power" => $this->minimumCriticalPower,
			"projectile_entity" => $this->projectileEntity
		];
	}

	public function isProperty(): bool {
		return false;
	}
}