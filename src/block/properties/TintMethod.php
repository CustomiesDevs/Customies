<?php

namespace customiesdevs\customies\block\properties;

enum TintMethod: string {
	case NONE = "none";
	case DEFAULT_FOLIAGE = "default_foliage";
	case BIRCH_FOLIAGE = "birch_foliage";
	case EVERGREEN_FOLIAGE = "evergreen_foliage";
	case DRY_FOLIAGE = "dry_foliage";
	case GRASS = "grass";
	case WATER = "water";
}