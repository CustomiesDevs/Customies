<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

use customiesdevs\customies\item\properties\EffectType;

final class FoodComponent implements ItemComponent {

	/** Default eating behavior */
	public const USE_ACTION_NORMAL = -1;
	/** Chorus fruit teleport */
	public const USE_ACTION_CHORUS_TELEPORT = 0;
	/** Suspicious stew effect handler */
	public const USE_ACTION_SUSPICIOUS_STEW_EFFECT = 1;

	public const MODIFIER_POOR = 0.1;
	public const MODIFIER_LOW = 0.3;
	public const MODIFIER_NORMAL = 0.6;
	public const MODIFIER_GOOD = 0.8;
	public const MODIFIER_SUPERNATURAL = 1.2;

	/** No cooldown type */
	public const COOLDOWN_DEFAULT = "";
	/** Chorus fruit cooldown type (forces 20 ticks) */
	public const COOLDOWN_CHORUSFRUIT = "chorusfruit";

	private bool $canAlwaysEat;
	private int $nutrition;
	private float $saturationModifier;
	private string $usingConvertsTo;
	private ?int $cooldownTime = null;
	private ?string $cooldownType = null;
	private ?int $onUseAction = null;
	/** @var array{0: float, 1: float, 2: float}|null */
	private ?array $onUseRange = null;
	/**
	 * Potion effects applied when the food is eaten.
	 *
	 * @var array<int, array{
	 *   name: string,
	 *   id: int,
	 *   descriptionId: string,
	 *   duration: int,
	 *   amplifier: int,
	 *   chance: float
	 * }>
	 */
	private array $effects = [];
	/**
	 * List of potion effect IDs to remove when the food is consumed.
	 * @var int[]
	 */
	private array $removeEffects = [];

	/**
	 * Sets the item as a food component, allowing it to be edible to the player.
	 * @param bool $canAlwaysEat Whether the player can always eat this food, even when not hungry. Default is false.
	 * @param int $nutrition The amount of hunger points this food item restores when eaten. Default is 0.
	 * @param float $saturationModifier The saturation modifier for this food item. Default is `MODIFIER_NORMAL` (0.6).
	 * @param string $usingConvertsTo The item this food converts to after being consumed. Default is an empty string.
	 */
	public function __construct(
		bool $canAlwaysEat = false,
		int $nutrition = 0,
		float $saturationModifier = self::MODIFIER_NORMAL,
		string $usingConvertsTo = ""
	) {
		$this->canAlwaysEat = $canAlwaysEat;
		$this->nutrition = $nutrition;
		$this->saturationModifier = $saturationModifier;
		$this->usingConvertsTo = $usingConvertsTo;
	}

	public function getName(): string {
		return 'minecraft:food';
	}

	public function getValue(): array {
		$data = [
			"can_always_eat" => $this->canAlwaysEat,
			"nutrition" => $this->nutrition,
			"saturation_modifier" => $this->saturationModifier,
			"using_converts_to" => $this->usingConvertsTo
		];
		if($this->cooldownTime !== null){
			$data['cooldown_time'] = $this->cooldownTime;
			$data['cooldown_type'] = $this->cooldownType ?? self::COOLDOWN_DEFAULT;
		}
		if($this->onUseAction !== null){
			$data["on_use_action"] = $this->onUseAction;
			$data["on_use_range"] = $this->onUseRange ?? [8.0, 8.0, 8.0];
		}
		if($this->effects !== []){
			$data["effects"] = $this->effects;
		}
		if($this->removeEffects !== []){
			$data['remove_effects'] = $this->removeEffects;
		}
		return $data;
	}

	public function getPropertyMapping(): ?array {
		return null;
	}

	/**
	 * Sets a cooldown after eating.
	 * If `COOLDOWN_CHORUSFRUIT` is used, cooldown time is forced to 20 ticks.
	 * @param int $time Cooldown duration in ticks
	 * @param string $type Cooldown type
	 */
	public function addCooldown(int $time = 0, string $type = self::COOLDOWN_DEFAULT): self {
		if($time < 0){
			throw new \InvalidArgumentException("Cooldown time must be >= 0");
		}
		$this->cooldownType = $type;
		$this->cooldownTime = ($type === self::COOLDOWN_CHORUSFRUIT) ? 20 : $time;
		return $this;
	}

	/**
	 * Defines an on-use event action.
	 * @param int $action One of the USE_ACTION_* constants
	 * @param float $x Effect range X
	 * @param float $y Effect range Y
	 * @param float $z Effect range Z
	 */
	public function onUseEvent(
		int $action = self::USE_ACTION_NORMAL,
		float $x = 8.0,
		float $y = 8.0,
		float $z = 8.0
	): self {
		if(!in_array($action, [
			self::USE_ACTION_NORMAL,
			self::USE_ACTION_CHORUS_TELEPORT,
			self::USE_ACTION_SUSPICIOUS_STEW_EFFECT
		], true)){
			throw new \InvalidArgumentException("Invalid on_use_action: $action");
		}
		$this->onUseAction = $action;
		$this->onUseRange = [$x, $y, $z];
		return $this;
	}

	/**
	 * Adds a potion effect applied when the food is consumed.
	 * @param EffectType $effect Potion effect type
	 * @param int $duration Effect duration in seconds
	 * @param int $amplifier Effect strength (0 = level I)
	 * @param float $chance Chance to apply the effect)
	 */
	public function addEffect(
		EffectType $effect,
		int $duration,
		int $amplifier = 0,
		float $chance = 1.0
	): self {
		if($duration <= 0){
			throw new \InvalidArgumentException("Effect duration must be > 0");
		}
		if($amplifier < 0){
			throw new \InvalidArgumentException("Effect amplifier must be >= 0");
		}
		$this->effects[] = [
			"name" => $effect->getName(),
			"id" => $effect->getId(),
			"descriptionId" => $effect->getDescriptionId(),
			"duration" => $duration,
			"amplifier" => $amplifier,
			"chance" => $chance
		];
		return $this;
	}

	/**
	 * Removes one or more potion effects when the food is consumed.
	 * @param EffectType ...$effects Effects to remove
	 */
	public function removeEffects(EffectType ...$effects): self {
		foreach($effects as $effect){
			$this->removeEffects[] = $effect->getId();
		}
		$this->removeEffects = array_values(array_unique($this->removeEffects));
		return $this;
	}
}