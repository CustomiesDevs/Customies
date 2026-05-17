<?php

namespace customiesdevs\customies\block\properties;

enum RenderMethod: string {
	case ALPHA_TEST = "alpha_test";
	case ALPHA_TEST_SINGLE_SIDED = "alpha_test_single_sided";
	case BLEND = "blend";
	case DOUBLE_SIDED = "double_sided";
	case OPAQUE = "opaque";

	// Distance-Based Render Methods
	case ALPHA_TEST_TO_OPAQUE = "alpha_test_to_opaque";
	case ALPHA_TEST_SINGLE_SIDED_TO_OPAQUE = "alpha_test_single_sided_to_opaque";
	case BLEND_TO_OPAQUE = "blend_to_opaque";
}