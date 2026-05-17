<?php

namespace customiesdevs\customies\block\properties;

enum CullingShape: string {
	/**
	 * A single 16×16×16 pixel cube, taking up the space of the full block unit.
	 * All blocks using the minecraft:geometry.full_block model are forced to use this voxel shape for culling.
	*/
	case SHAPE_UNIT_CUBE = "minecraft:unit_cube";
	/**
	 * An empty shape that contains no boxes.
	 * The default culling shape for blocks not using the minecraft:geometry.full_block model.
	 */
	case SHAPE_EMPTY = "minecraft:empty";

	/** A single 16×8×16 pixel box, taking up space from the top of the block unit. */
	case SHAPE_SLAB_TOP = "minecraft:slab_type_top";
	/** A single 16×15×16 pixel box, taking up space from the bottom of the block unit. */
	case SHAPE_16x15x16 = "minecraft:box_16x15x16";
	/** A single 16×14×16 pixel box, taking up space from the bottom of the block unit. */
	case SHAPE_16x14x16 = "minecraft:box_16x14x16";
	/** A single 16×13×16 pixel box, taking up space from the bottom of the block unit. */
	case SHAPE_16x13x16 = "minecraft:box_16x13x16";
	/** A single 16×12×16 pixel box, taking up space from the bottom of the block unit. */
	case SHAPE_16x12x16 = "minecraft:box_16x12x16";
	/** A single 16×10×16 pixel box, taking up space from the bottom of the block unit. */
	case SHAPE_16x10x16 = "minecraft:box_16x10x16";
	/** A single 16×9×16 pixel box, taking up space from the bottom of the block unit. */
	case SHAPE_16x9x16 = "minecraft:box_16x9x16";
	/** A single 16×8×16 pixel box, taking up space from the bottom of the block unit. */
	case SHAPE_16x8x16 = "minecraft:box_16x8x16";
	/** A single 16×6×16 pixel box, taking up space from the bottom of the block unit. */
	case SHAPE_16x6x16 = "minecraft:box_16x6x16";
	/** A single 16×4×16 pixel box, taking up space from the bottom of the block unit. */
	case SHAPE_16x4x16 = "minecraft:box_16x4x16";
	/** A single 16×2×16 pixel box, taking up space from the bottom of the block unit. */
	case SHAPE_16x2x16 = "minecraft:box_16x2x16";
	/** A single 16×1×16 pixel box, taking up space from the bottom of the block unit. */
	case SHAPE_16x1x16 = "minecraft:box_16x1x16";
}