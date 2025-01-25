<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class ProjectileComponent implements ItemComponent {

	private float $minimumCriticalPower;
	private string $projectileEntity;

	/**
	 * Projectile Component compels the item to shoot, similarly to an arrow. 
	 * Items with projectile component can be shot from dispensers or used as ammunition for items with the shooter item component. 
	 * Additionally, this component sets the entity that is spawned for items that also contain the throwable component.
	 * @param float $minimumCriticalPower Specifies how long a player must charge a projectile for it to critically hit
	 * @param string $projectileEntity Which entity is to be fired as a projectile
	 */
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