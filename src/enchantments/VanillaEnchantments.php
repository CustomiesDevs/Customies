<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
 */

declare(strict_types=1);

namespace customiesdevs\customies\enchantments;

use pocketmine\item\enchantment\{Rarity, ProtectionEnchantment, Enchantment, SharpnessEnchantment, KnockbackEnchantment, FireAspectEnchantment};
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\utils\RegistryTrait;

/**
 * This doc-block is generated automatically, do not modify it manually.
 * This must be regenerated whenever registry members are added, removed or changed.
 * @see build/generate-registry-annotations.php
 * @generate-registry-docblock
 *
 * @method static ProtectionEnchantment BLAST_PROTECTION()
 * @method static Enchantment EFFICIENCY()
 * @method static ProtectionEnchantment FEATHER_FALLING()
 * @method static FireAspectEnchantment FIRE_ASPECT()
 * @method static ProtectionEnchantment FIRE_PROTECTION()
 * @method static Enchantment FLAME()
 * @method static Enchantment FORTUNE()
 * @method static Enchantment INFINITY()
 * @method static KnockbackEnchantment KNOCKBACK()
 * @method static Enchantment MENDING()
 * @method static Enchantment POWER()
 * @method static ProtectionEnchantment PROJECTILE_PROTECTION()
 * @method static ProtectionEnchantment PROTECTION()
 * @method static Enchantment PUNCH()
 * @method static Enchantment RESPIRATION()
 * @method static SharpnessEnchantment SHARPNESS()
 * @method static Enchantment SILK_TOUCH()
 * @method static Enchantment SWIFT_SNEAK()
 * @method static Enchantment THORNS()
 * @method static Enchantment UNBREAKING()
 * @method static Enchantment VANISHING()
 */
final class VanillaEnchantments{
	use RegistryTrait;

	protected static function setup() : void{
		self::register("PROTECTION", new ProtectionEnchantment(
			"Protection",
			Rarity::COMMON,
			0,
			0,
			4,
			0.75,
			null,
			fn(int $level) : int => 11 * ($level - 1) + 1,
			20
		));
		self::register("FIRE_PROTECTION", new ProtectionEnchantment(
			"§6Fire Protection",
			Rarity::UNCOMMON,
			0,
			0,
			4,
			1.25,
			[
				EntityDamageEvent::CAUSE_FIRE,
				EntityDamageEvent::CAUSE_FIRE_TICK,
				EntityDamageEvent::CAUSE_LAVA
				//TODO: check fireballs
			],
			fn(int $level) : int => 8 * ($level - 1) + 10,
			12
		));
		self::register("FEATHER_FALLING", new ProtectionEnchantment(
			"§7Feather Falling",
			Rarity::UNCOMMON,
			0,
			0,
			4,
			2.5,
			[
				EntityDamageEvent::CAUSE_FALL
			],
			fn(int $level) : int => 6 * ($level - 1) + 5,
			10
		));
		self::register("BLAST_PROTECTION", new ProtectionEnchantment(
			"§cBlast Protection",
			Rarity::RARE,
			0,
			0,
			4,
			1.5,
			[
				EntityDamageEvent::CAUSE_BLOCK_EXPLOSION,
				EntityDamageEvent::CAUSE_ENTITY_EXPLOSION
			],
			fn(int $level) : int => 8 * ($level - 1) + 5,
			12
		));
		self::register("PROJECTILE_PROTECTION", new ProtectionEnchantment(
			"§7Projectile Protection",
			Rarity::UNCOMMON,
			0,
			0,
			4,
			1.5,
			[
				EntityDamageEvent::CAUSE_PROJECTILE
			],
			fn(int $level) : int => 6 * ($level - 1) + 3,
			15
		));
		self::register("THORNS", new Enchantment(
			"§bThorns",
			Rarity::MYTHIC,
			0,
			0,
			3,
			fn(int $level) : int => 20 * ($level - 1) + 10,
			50
		));
		self::register("RESPIRATION", new Enchantment(
			"Oxygen",
			Rarity::RARE,
			0,
			0,
			3,
			fn(int $level) : int => 10 * $level,
			30
		));

		self::register("SHARPNESS", new SharpnessEnchantment(
			"§7Sharpness",
			Rarity::COMMON,
			0,
			0,
			5,
			fn(int $level) : int => 11 * ($level - 1) + 1,
			20
		));
		self::register("KNOCKBACK", new KnockbackEnchantment(
			"Knockback",
			Rarity::UNCOMMON,
			0,
			0,
			2,
			fn(int $level) : int => 20 * ($level - 1) + 5,
			50
		));
		self::register("FIRE_ASPECT", new FireAspectEnchantment(
			"§6Fire Aspect",
			Rarity::RARE,
			0,
			0,
			2,
			fn(int $level) : int => 20 * ($level - 1) + 10,
			50
		));
		//TODO: smite, bane of arthropods, looting (these don't make sense now because their applicable mobs don't exist yet)

		self::register("EFFICIENCY", new Enchantment(
			"§2Efficiency",
			Rarity::COMMON,
			0,
			0,
			5,
			fn(int $level) : int => 10 * ($level - 1) + 1,
			50
		));
		self::register("FORTUNE", new Enchantment(
			"§eFortune",
			Rarity::RARE,
			0,
			0,
			3,
			fn(int $level) : int => 9 * ($level - 1) + 15,
			50
		));
		self::register("SILK_TOUCH", new Enchantment(
			"§7Silk touch",
			Rarity::MYTHIC,
			0,
			0,
			1,
			fn(int $level) : int => 15,
			50
		));
		self::register("UNBREAKING", new Enchantment(
			"Unbreaking",
			Rarity::UNCOMMON,
			0,
			0,
			3,
			fn(int $level) : int => 8 * ($level - 1) + 5,
			50
		));

		self::register("POWER", new Enchantment(
			"§bPower",
			Rarity::COMMON,
			0,
			0,
			5,
			fn(int $level) : int => 10 * ($level - 1) + 1,
			15
		));
		self::register("PUNCH", new Enchantment(
			"§cPunch",
			Rarity::RARE,
			0,
			0,
			2,
			fn(int $level) : int => 20 * ($level - 1) + 12,
			25
		));
		self::register("FLAME", new Enchantment(
			"§6Flame",
			Rarity::RARE,
			0,
			0,
			1,
			fn(int $level) : int => 20,
			30
		));
		self::register("INFINITY", new Enchantment(
			"§dInfinity",
			Rarity::MYTHIC,
			0,
			0,
			1,
			fn(int $level) : int => 20,
			30
		));

		self::register("MENDING", new Enchantment(
			"§7Mending",
			Rarity::RARE,
			0,
			0,
			1,
			fn(int $level) : int => 25,
			50
		));

		self::register("VANISHING", new Enchantment(
			"§uVanishing",
			Rarity::MYTHIC,
			0,
			0,
			1,
			fn(int $level) : int => 25,
			25
		));

		self::register("SWIFT_SNEAK", new Enchantment(
			"§7Swift sneak",
			Rarity::MYTHIC,
			0,
			0,
			3,
			fn(int $level) : int => 10 * $level,
			5
		));
	}

	protected static function register(string $name, Enchantment $member) : void{
		self::_registryRegister($name, $member);
	}

	/**
	 * @return Enchantment[]
	 * @phpstan-return array<string, Enchantment>
	 */
	public static function getAll() : array{
		/**
		 * @var Enchantment[] $result
		 * @phpstan-var array<string, Enchantment> $result
		 */
		$result = self::_registryGetAll();
		return $result;
	}
}
