<?php

namespace customiesdevs\customies\block\component;

use pocketmine\nbt\tag\ShortTag;

final class DestructibleByMiningComponent implements BlockComponent {

	/** Seconds to destroy with base equipment */
	private float $secondsToDestroy;
	/**
	 * @var array<int, array{
	 *   destroy_speed: float,
	 *   item: string|array{
	 *     MolangVersion?: int,
	 *     tags?: string
	 *   }
	 * }>
	 */
	private array $itemSpecificSpeeds = [];

	/**
	 * Describes the destructible by mining properties for this block.
	 * @param float $secondsToDestroy Sets the number of seconds it takes to destroy the block with base equipment. Greater numbers result in greater mining times.
	 */
	public function __construct(float $secondsToDestroy = 0.0) {
		if($secondsToDestroy < 0){
			throw new \InvalidArgumentException("secondsToDestroy must be >= 0");
		}
		$this->secondsToDestroy = $secondsToDestroy;
	}

	public function getName(): string {
		return 'minecraft:destructible_by_mining';
	}

	public function getValue(): array {
		$data = [
			"value" => $this->secondsToDestroy
		];
		if($this->itemSpecificSpeeds !== []){
			$data["item_specific_speeds"] = $this->itemSpecificSpeeds;
		}
		return $data;
	}

	/**
	 * Adds an item-specific destroy speed using item tags (Molang).
	 * @param float $destroySpeed
	 * @param string $tags Molang tag expression
	 * @param int $molangVersion
	 */
	public function addItemSpeedByTags(
		float $destroySpeed,
		string $tags,
	): self {
		if($destroySpeed <= 0){
			throw new \InvalidArgumentException("destroy_speed must be > 0");
		}
		$this->itemSpecificSpeeds[] = [
			"destroy_speed" => $destroySpeed,
			"item" => [
				"MolangVersion" => new ShortTag(13),
				"Tags" => $tags
			]
		];
		return $this;
	}

	/**
	 * Adds an item-specific destroy speed using an item identifier.
	 * @param float $destroySpeed
	 * @param string $itemId
	 */
	public function addItemSpeedByItem(
		float $destroySpeed,
		string $itemId
	): self {
		if($destroySpeed <= 0){
			throw new \InvalidArgumentException("destroy_speed must be > 0");
		}
		$this->itemSpecificSpeeds[] = [
			"destroy_speed" => $destroySpeed,
			"item" => $itemId
		];
		return $this;
	}
}