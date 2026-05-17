<?php

namespace customiesdevs\customies\block\component;

final class LeashableComponent implements BlockComponent {

	/** @var float[] */
	private array $offset;

	/**
	 * Allows leads to be attached to the block like fences,
	 * Contains the offset of the leash knot position,
	 * which is the point where the lead will visually attach to the block.
	 * The offset is defined as an array of three floats representing the x, y, and z offsets from the bottom middle of the block.
	 * For example, an offset of `[0.0, 0.25, 0.0]` would position the leash knot slightly above the center of the block.
	 * 
	 * @param float[] $offset An array of three floats representing the x, y, and z offsets for the leash knot position.
	 */
	public function __construct(array $offset = [0.0, 0.25, 0.0]) {
		if(count($offset) !== 3){
			throw new \InvalidArgumentException("Leashable offset must contain exactly 3 values");
		}
		$this->offset = array_values($offset);
	}

	public function getName(): string {
		return 'minecraft:leashable';
	}

	public function getValue(): array {
		return [
			"offset" => $this->offset
		];
	}
}