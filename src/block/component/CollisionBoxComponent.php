<?php
declare(strict_types=1);

namespace customiesdevs\customies\block\component;

use customiesdevs\customies\block\properties\Box;

final class CollisionBoxComponent implements BlockComponent {

	private bool $enabled;
	/** @var Box[] */
	private array $boxes = [];

	/**
	 * Defines the area of the block that collides with entities.
	 * @param bool $enabled If collision should be enabled
	 */
	public function __construct(bool $enabled = true) {
		$this->enabled = $enabled;
	}

	public function getName(): string {
		return 'minecraft:collision_box';
	}

	public function getValue(): array {
		$boxes = [];
		foreach($this->boxes as $box){
			$boxes[] = $box->toNbtArray();
		}
		// if no boxes are defined we add a default full block box
		if($this->enabled && empty($boxes)){
			$boxes[] = Box::defaultBox()->toNbtArray();
		}
		// no collision
		if(!$this->enabled){
			$boxes[] = [];
		}
		return [
			"boxes" => $boxes,
			"enabled" => $this->enabled
		];
	}

	/**
	 * Adds a single collision box.
	 * @param Box $box
	 * The collision box to add.
	 * @return $this
	 */
	public function addBox(Box $box): self {
		if(count($this->boxes) === 1){
			$this->boxes = [];
		}
		$this->boxes[] = $box;
		return $this;
	}

	/**
	 * Adds multiple collision boxes.
	 * @param Box[] $boxes
	 * An array of collision boxes to add.
	 * @return $this
	 * @throws \InvalidArgumentException If any element in the array is not an instance of Box.
	 */
	public function addBoxes(array $boxes): self {
		if(count($this->boxes) === 1){
			$this->boxes = [];
		}
		foreach($boxes as $box){
			if(!$box instanceof Box){
				throw new \InvalidArgumentException("All boxes must be instances of " . Box::class);
			}
			$this->boxes[] = $box;
		}
		return $this;
	}
}