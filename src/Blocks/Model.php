<?php
declare(strict_types=1);

namespace customiesdevs\customies\Blocks;

use customiesdevs\customies\Blocks\Material;use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\ListTag;

final class Model {

	/** @var Material[] */
	private array $materials;
	private ?string $geometry;
    private ?bool $flowable;
	private Vector3 $origin;
	private Vector3 $size;

    public static $material;

    private bool $selection_box;

	/**
	 * @param Material[] $materials
	 */
	public function __construct(array $materials, ?string $geometry = null, ?Vector3 $origin = null, ?Vector3 $size = null, ?bool $flowable = false, ?bool $selection_box = true) {
		$this->materials = $materials;
		$this->geometry = $geometry;
		$this->origin = $origin ?? Vector3::zero();
		$this->size = $size ?? Vector3::zero();
        $this->flowable = $flowable;
        $this->selection_box = $selection_box;
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
                ->setByte($this->flowable ? "disable" : "enabled", 1)
                ->setTag("origin", new ListTag([
                    new FloatTag($this->origin->getX()),
                    new FloatTag($this->origin->getY()),
                    new FloatTag($this->origin->getZ())
                ]))
                ->setTag("size", new ListTag([
                    new FloatTag($this->size->getX()),
                    new FloatTag($this->size->getY()),
                    new FloatTag($this->size->getZ())
                ]));
			$material["minecraft:selection_box"] = CompoundTag::create()
				->setByte($this->selection_box ? "enabled" : "disabled", 1)
				->setTag("origin", new ListTag([
					new FloatTag($this->origin->getX()),
					new FloatTag($this->origin->getY()),
					new FloatTag($this->origin->getZ())
				]))
				->setTag("size", new ListTag([
					new FloatTag($this->size->getX()),
					new FloatTag($this->size->getY()),
					new FloatTag($this->size->getZ())
				]));
		}
		return $material;
	}
}
