<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class ProjectileComponent extends BasicComponent {

	public function __construct(string $projectileEntity) {
        parent::__construct("minecraft:projectile", [
            "entity" => $projectileEntity
        ], false);
	}
}