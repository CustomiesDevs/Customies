<?php

namespace customiesdevs\customies\block\enum;

enum RenderMethod: string
{
	case ALPHA_TEST = "alpha_test";
	case BLEND = "blend";
	case OPAQUE = "opaque";
}
