<?php
declare(strict_types=1);

namespace customiesdevs\customies\block\properties;

/**
 * Valid placement faces for minecraft:placement_filter
 */
enum AllowedFace: string {
	case UP = "up";
	case DOWN = "down";
	case NORTH = "north";
	case SOUTH = "south";
	case EAST = "east";
	case WEST = "west";
	case SIDE = "side";
	case ALL = "all";
}