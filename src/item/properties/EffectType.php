<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\properties;

use pocketmine\lang\KnownTranslationKeys;

enum EffectType: int {
	case SPEED = 1;
	case SLOWNESS = 2;
	case HASTE = 3;
	case MINING_FATIGUE = 4;
	case STRENGTH = 5;
	case INSTANT_HEALTH = 6;
	case INSTANT_DAMAGE = 7;
	case JUMP_BOOST = 8;
	case NAUSEA = 9;
	case REGENERATION = 10;
	case RESISTANCE = 11;
	case FIRE_RESISTANCE = 12;
	case WATER_BREATHING = 13;
	case INVISIBILITY = 14;
	case BLINDNESS = 15;
	case NIGHT_VISION = 16;
	case HUNGER = 17;
	case WEAKNESS = 18;
	case POISON = 19;
	case WITHER = 20;
	case HEALTH_BOOST = 21;
	case ABSORPTION = 22;
	case SATURATION = 23;
	case LEVITATION = 24;
	case FATAL_POISON = 25;
	case CONDUIT_POWER = 26;
	case SLOW_FALLING = 27;
	case BAD_OMEN = 28;
	case VILLAGE_HERO = 29;
	case DARKNESS = 30;

	public function getName(): string {
		return strtolower($this->name);
	}

	public function getDescriptionId(): string {
		return match($this){
			self::REGENERATION      => KnownTranslationKeys::POTION_REGENERATION,
			self::ABSORPTION        => KnownTranslationKeys::POTION_ABSORPTION,
			self::RESISTANCE        => KnownTranslationKeys::POTION_RESISTANCE,
			self::FIRE_RESISTANCE   => KnownTranslationKeys::POTION_FIRERESISTANCE,
			self::POISON, self::FATAL_POISON      => KnownTranslationKeys::POTION_POISON,
			self::WITHER            => KnownTranslationKeys::POTION_WITHER,
			self::SPEED             => KnownTranslationKeys::POTION_MOVESPEED,
			self::SLOWNESS          => KnownTranslationKeys::POTION_MOVESLOWDOWN,
			self::HASTE             => KnownTranslationKeys::POTION_DIGSPEED,
			self::MINING_FATIGUE    => KnownTranslationKeys::POTION_DIGSLOWDOWN,
			self::STRENGTH          => KnownTranslationKeys::POTION_DAMAGEBOOST,
			self::INSTANT_HEALTH    => KnownTranslationKeys::POTION_HEAL,
			self::INSTANT_DAMAGE    => KnownTranslationKeys::POTION_HARM,
			self::JUMP_BOOST        => KnownTranslationKeys::POTION_JUMP,
			self::NAUSEA            => KnownTranslationKeys::POTION_CONFUSION,
			self::BLINDNESS         => KnownTranslationKeys::POTION_BLINDNESS,
			self::NIGHT_VISION      => KnownTranslationKeys::POTION_NIGHTVISION,
			self::HUNGER            => KnownTranslationKeys::POTION_HUNGER,
			self::WEAKNESS          => KnownTranslationKeys::POTION_WEAKNESS,
			self::HEALTH_BOOST      => KnownTranslationKeys::POTION_HEALTHBOOST,
			self::SATURATION        => KnownTranslationKeys::POTION_SATURATION,
			self::LEVITATION        => KnownTranslationKeys::POTION_LEVITATION,
			self::CONDUIT_POWER     => KnownTranslationKeys::POTION_CONDUITPOWER,
			self::SLOW_FALLING      => KnownTranslationKeys::POTION_SLOWFALLING,
			self::BAD_OMEN          => "effect.badOmen",
			self::VILLAGE_HERO      => "effect.villageHero",
			self::DARKNESS          => KnownTranslationKeys::EFFECT_DARKNESS,
			self::WATER_BREATHING   => KnownTranslationKeys::POTION_WATERBREATHING,
		};
	}

	public function getId(): int {
		return $this->value;
	}
}