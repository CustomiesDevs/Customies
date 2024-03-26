<?php
declare(strict_types=1);

namespace customiesdevs\customies\block;

use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\ListTag;

final class Model {

	/** @var Material[] */
	private array $materials;
	private ?string $geometry;
    private Vector3 $originForCollisionBox;
    private Vector3 $sizeForCollisionBox;
	private Vector3 $originForSelectionBox;
	private Vector3 $sizeForSelectionBox;

	/**
	 * @param Material[] $materials
	 */
	public function __construct(
        array $materials,
        ?string $geometry = null,
        ?Vector3 $originForCollisionBox = null,
        ?Vector3 $sizeForCollisionBox = null,
        ?Vector3 $originForSelectionBox = null,
        ?Vector3 $sizeForSelectionBox = null
    ){
		$this->materials = $materials;
		$this->geometry = $geometry;
		$this->originForCollisionBox = $originForCollisionBox ?? Vector3::zero();
		$this->sizeForCollisionBox = $sizeForCollisionBox ?? Vector3::zero();
        $this->originForSelectionBox = $originForSelectionBox ?? Vector3::zero();
        $this->sizeForSelectionBox = $sizeForSelectionBox ?? Vector3::zero();
	}

	/**
	 * Returns the model in the correct NBT format supported by the client.
	 * @return CompoundTag[]
	 */
	public function toNBT(): array {
		$materials = CompoundTag::create();
		foreach($this->materials as $material){
			$materials->setTag($material->getTarget(), $material->toNBT());
		}

		$material = [
			"minecraft:material_instances" => CompoundTag::create()
				->setTag("mappings", CompoundTag::create()) // What is this? The client will crash if it is not sent.
				->setTag("materials", $materials),
		];
		if($this->geometry === null) {
			$material["minecraft:unit_cube"] = CompoundTag::create();
		} else {
			$material["minecraft:geometry"] = CompoundTag::create()
				->setString("identifier", $this->geometry);
			$material["minecraft:collision_box"] = CompoundTag::create()
				->setByte("enabled", 1)
				->setTag("origin", new ListTag([
					new FloatTag($this->originForCollisionBox->getX()),
					new FloatTag($this->originForCollisionBox->getY()),
					new FloatTag($this->originForCollisionBox->getZ())
				]))
				->setTag("size", new ListTag([
					new FloatTag($this->sizeForCollisionBox->getX()),
					new FloatTag($this->sizeForCollisionBox->getY()),
					new FloatTag($this->sizeForCollisionBox->getZ())
				]));
			$material["minecraft:selection_box"] = CompoundTag::create()
				->setByte("enabled", 1)
				->setTag("origin", new ListTag([
					new FloatTag($this->originForSelectionBox->getX()),
					new FloatTag($this->originForSelectionBox->getY()),
					new FloatTag($this->originForSelectionBox->getZ())
				]))
				->setTag("size", new ListTag([
					new FloatTag($this->sizeForSelectionBox->getX()),
					new FloatTag($this->sizeForSelectionBox->getY()),
					new FloatTag($this->sizeForSelectionBox->getZ())
				]));
		}
		return $material;
	}
}
