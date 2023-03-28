<?php

namespace customiesdevs\customies\block\enum;

enum Target: string {
	case ALL = "*";
	case SIDES = "sides";
	case UP = "up";
	case DOWN = "down";
	case NORTH = "north";
	case EAST = "east";
	case SOUTH = "south";
	case WEST = "west";
}
