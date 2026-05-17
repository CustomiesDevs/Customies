<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\properties;

/**
 * Represents all possible causes of damage in the game.
 */
enum DamageCause: string {
	case NONE = "none";
	case ALL = "all";
	case ANVIL = "anvil";
	case BLOCK_EXPLOSION = "block_explosion";
	case CAMPFIRE = "campfire";
	case CHARGING = "charging";
	case CONTACT = "contact";
	case DROWNING = "drowning";
	case ENTITY_ATTACK = "entity_attack";
	case ENTITY_EXPLOSION = "entity_explosion";
	case FALL = "fall";
	case FALLING_BLOCK = "falling_block";
	case FIRE = "fire";
	case FIRE_TICK = "fire_tick";
	case FIREWORKS = "fireworks";
	case FLY_INTO_WALL = "fly_into_wall";
	case FREEZING = "freezing";
	case LAVA = "lava";
	case LIGHTNING = "lightning";
	case MAGIC = "magic";
	case MAGMA = "magma";
	case OVERRIDE = "override";
	case PISTON = "piston";
	case PROJECTILE = "projectile";
	case RAM_ATTACK = "ram_attack";
	case SELF_DESTRUCT = "self_destruct";
	case SONIC_BOOM = "sonic_boom";
	case SOUL_CAMPFIRE = "soul_campfire";
	case STALACTITE = "stalactite";
	case STALAGMITE = "stalagmite";
	case STARVE = "starve";
	case SUFFOCATION = "suffocation";
	case TEMPERATURE = "temperature";
	case THORNS = "thorns";
	case VOID = "void";
	case WITHER = "wither";
}