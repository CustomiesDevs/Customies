<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

use pocketmine\nbt\tag\ShortTag;

final class KineticWeaponComponent implements ItemComponent {

	/** @var array The reach of the weapon in creative mode */
	private array $creativeReach;
	/** @var array The conditions that must be met for the weapon to deal damage */
	private array $damageConditions;
	/** @var float A flat modifier added to the damage dealt by the weapon */
	private float $damageModifier;
	/** @var float A multiplier applied to the damage dealt by the weapon */
	private float $damageMultiplier;
	/** @var int The delay between uses of the weapon, in ticks */
	private int $delay;
	/** @var array The conditions that must be met for the weapon to dismount entities */
	private array $dismountConditions;
	/** @var float The margin added to the hitbox of the weapon */
	private float $hitboxMargin;
	/** @var array The conditions that must be met for the weapon to apply knockback */
	private array $knockbackConditions;
	/** @var array The reach of the weapon in survival mode */
	private array $reach;

	/**
	 * The kinetic weapon component defines the behavior of kinetic weapons, which deal damage based on their speed and other conditions.
	 * @param array $creativeReach The reach of the weapon in creative mode
	 * @param array $damageConditions Conditions that must be met for the weapon to deal damage
	 * @param float $damageModifier A flat modifier added to the damage dealt by the weapon
	 * @param float $damageMultiplier A multiplier applied to the damage dealt by the weapon
	 * @param int $delay The delay between uses of the weapon, in ticks
	 * @param array $dismountConditions Conditions that must be met for the weapon to dismount entities
	 * @param float $hitboxMargin The margin added to the hitbox of the weapon
	 * @param array $knockbackConditions Conditions that must be met for the weapon to apply knockback
	 * @param array $reach The reach of the weapon in survival mode
	 */
	public function __construct(
		array $creativeReach = ['max' => 7.5, 'min' => 2.0],
		array $damageConditions = [
			'max_duration' => 300,
			'min_relative_speed' => 4.6,
			'min_speed' => 0.0
		],
		float $damageModifier = 0.0,
		float $damageMultiplier = 0.7,
		int $delay = 15,
		array $dismountConditions = [
			'max_duration' => 100,
			'min_relative_speed' => 0.0,
			'min_speed' => 14.0
		],
		float $hitboxMargin = 0.25,
		array $knockbackConditions = [
			'max_duration' => 130,
			'min_relative_speed' => 0.0,
			'min_speed' => 5.1
		],
		array $reach = ['max' => 4.5, 'min' => 2.0]
	) {
		$this->creativeReach = self::validateRange($creativeReach, 'creative_reach');
		$this->reach = self::validateRange($reach, 'reach');
		$this->damageConditions = self::validateConditions($damageConditions, 'damage_conditions');
		$this->dismountConditions = self::validateConditions($dismountConditions, 'dismount_conditions');
		$this->knockbackConditions = self::validateConditions($knockbackConditions, 'knockback_conditions');
		$this->damageModifier = $damageModifier;
		$this->damageMultiplier = $damageMultiplier;
		$this->delay = $delay;
		$this->hitboxMargin = $hitboxMargin;
	}

	public function getName(): string {
		return 'minecraft:kinetic_weapon';
	}

	public function getValue(): array {
		return [
			"minecraft:kinetic_weapon" => [
				"creative_reach" => self::rangeToArray($this->creativeReach),
				"damage_conditions" => self::conditionsToArray($this->damageConditions),
				"damage_modifier" => (float) $this->damageModifier,
				"damage_multiplier" => (float) $this->damageMultiplier,
				"delay" => new ShortTag($this->delay),
				"dismount_conditions" => self::conditionsToArray($this->dismountConditions),
				"hitbox_margin" => (float) $this->hitboxMargin,
				"knockback_conditions" => self::conditionsToArray($this->knockbackConditions),
				"reach" => self::rangeToArray($this->reach),
			]
		];
	}

	public function getPropertyMapping(): ?array {
		return null;
	}

	private static function validateRange(array $range, string $name): array {
		if(!isset($range['max'], $range['min'])){
			throw new \InvalidArgumentException("$name must contain min and max");
		}
		return [
			'max' => (float) $range['max'], // Float
			'min' => (float) $range['min'] // Float
		];
	}

	private static function validateConditions(array $conditions, string $name): array {
		foreach(['max_duration', 'min_relative_speed', 'min_speed'] as $key){
			if(!array_key_exists($key, $conditions)){
				throw new \InvalidArgumentException("$name missing $key");
			}
		}
		return [
			'max_duration' => (int) $conditions['max_duration'],
			'min_relative_speed' => (float) $conditions['min_relative_speed'],
			'min_speed' => (float) $conditions['min_speed']
		];
	}

	private static function rangeToArray(array $range): array {
		return [
			"max" => (float) $range['max'], // Float
			"min" => (float) $range['min'] // Float
		];
	}

	private static function conditionsToArray(array $conditions): array {
		return [
			"max_duration" => new ShortTag($conditions['max_duration']), // Short
			"min_relative_speed" => (float) $conditions['min_relative_speed'], // Float
			"min_speed" => (float) $conditions['min_speed'] // Float
		];
	}
}