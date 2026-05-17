<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

use customiesdevs\customies\item\properties\SoundEvent;

final class UseModifiersComponent implements ItemComponent {

	private float $useDuration;
	private float $movementModifier;
	private bool $emitVibrations;
	private ?string $startSound;

	/**
	 * Determines how an item behaves while being used.
	 * @param float       $movementModifier Modifier applied to player movement speed
	 * @param float       $useDuration       How long the item takes to use (seconds)
	 * @param bool        $emitVibrations    Whether the item emits vibration events
	 * @param SoundEvent|string|null $startSound        Sound played when use starts
	 */
	public function __construct(
		float $movementModifier = 1.0,
		float $useDuration = 0.0,
		bool $emitVibrations = false,
		SoundEvent|string|null $startSound = null
	) {
		$this->movementModifier = $movementModifier;
		$this->useDuration = $useDuration;
		$this->emitVibrations = $emitVibrations;
		$this->startSound = $startSound instanceof SoundEvent ? (string) $startSound->value : $startSound;
	}

	public function getName(): string {
		return 'minecraft:use_modifiers';
	}

	public function getValue(): array {
		$value = [
			"emit_vibrations" => $this->emitVibrations,
			"movement_modifier" => $this->movementModifier,
			"use_duration" => $this->useDuration
		];
		if($this->startSound !== null){
			$value['start_sound'] = $this->startSound;
		}
		return $value;
	}

	public function getPropertyMapping(): ?array {
		return ['use_duration' => (float) $this->useDuration];
	}
}