<?php
declare(strict_types=1);

namespace customiesdevs\customies\block\properties;

use customiesdevs\customies\block\properties\AllowedFace;
use InvalidArgumentException;

final class PlacementCondition {

	/** @var AllowedFace[] */
	private array $allowedFaces = [];
	/** @var BlockDescriptor[] */
	private array $blockFilters = [];

	/**
	 * @param AllowedFace[] $allowedFaces (max 6)
	 * @param BlockDescriptor[] $blockFilters (max 64)
	 */
	public function __construct(array $allowedFaces, array $blockFilters) {
		if(count($allowedFaces) > 6){
			throw new InvalidArgumentException("Placement condition may not exceed 6 allowed faces");
		}
		if(count($blockFilters) > 64){
			throw new InvalidArgumentException("Placement condition may not exceed 64 block filters");
		}
		$this->allowedFaces = $allowedFaces;
		$this->blockFilters = $blockFilters;
	}

	/**
	 * Converts the placement condition to Bedrock format.
	 */
	public function toArray(): array {
		return [
			"allowed_faces" => array_map(
				static fn(AllowedFace $f) => $f->value,
				$this->allowedFaces
			),
			"block_filter" => array_map(
				static fn(BlockDescriptor $b) => $b->toArray(),
				$this->blockFilters
			)
		];
	}

	/**
	 * Creates a PlacementCondition from an array.
	 */
	public static function fromArray(array $data): self {
		return new self(
			array_map(
				static fn(string $f) => AllowedFace::from($f),
				$data["allowed_faces"] ?? []
			),
			array_map(
				static fn(array $b) => BlockDescriptor::fromArray($b),
				$data["block_filter"] ?? []
			)
		);
	}
}