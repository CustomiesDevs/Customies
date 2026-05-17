<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

use customiesdevs\customies\item\properties\SoundEvent;

final class SwingSoundsComponent implements ItemComponent {

	public function __construct(
		private SoundEvent $critical = SoundEvent::ATTACK_CRITICAL,
		private SoundEvent $hit = SoundEvent::ATTACK_STRONG,
		private SoundEvent $miss = SoundEvent::ATTACK_NODAMAGE,
	) {}

	public function getName(): string {
		return "minecraft:swing_sounds";
	}

	public function getValue(): array {
		return [
			"attack_critical_hit" => $this->critical->value,
			"attack_hit" => $this->hit->value,
			"attack_miss" => $this->miss->value,
		];
	}

	public function getPropertyMapping(): ?array {
		return null;
	}
}