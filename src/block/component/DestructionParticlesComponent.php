<?php

namespace customiesdevs\customies\block\component;

use customiesdevs\customies\block\properties\TintMethod;

final class DestructionParticlesComponent implements BlockComponent {

	private int $particleCount;
	private string $texture;
	private TintMethod $tintMethod;

	/**
	 * Sets the particles that will be used when block is destroyed.
	 * @param int $particleCount Optional, number of particles to spawn of destruction. Default is 100, maximum is 255 inclusively
	 * @param string $texture The texture name used for the particle.
	 * @param TintMethod $tintMethod Tint multiplied to the color. Tint method logic varies, but often refers to the "rain" and "temperature" of the biome the block is placed in to compute the tint.
	 */
	public function __construct(
		int $particleCount = 100,
		string $texture = "",
		TintMethod $tintMethod = TintMethod::NONE
	) {
		$this->particleCount = max(0, min(255, $particleCount));
		$this->texture = $texture;
		$this->tintMethod = $tintMethod;
	}

	public function getName(): string {
		return 'minecraft:destruction_particles';
	}

	public function getValue(): array {
		return [
			"particle_count" => $this->particleCount,
			"texture" => $this->texture,
			"tint_method" => $this->tintMethod->value
		];
	}
}