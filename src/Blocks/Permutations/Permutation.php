<?php
declare(strict_types=1);

namespace customiesdevs\customies\Blocks\Permutations;

use customiesdevs\customies\Utils\NBT;
use pocketmine\nbt\tag\CompoundTag;

final class Permutation {

	private CompoundTag $components;

	public function __construct(private readonly string $condition) {
		$this->components = CompoundTag::create();
	}

	/**
	 * Returns the permutation with the provided component added to the current list of components.
	 */
	public function withComponent(string $component, mixed $value) : self {
		$this->components->setTag($component, NBT::getTagType($value));
		return $this;
	}

    public function getComponent(string $component) : CompoundTag  {
        $x = $this->components;
        return $x;
    }

	/**
	 * Returns the permutation in the correct NBT format supported by the client.
	 */
	public function toNBT(): CompoundTag {
		return CompoundTag::create()
			->setString("condition", $this->condition)
			->setTag("components", $this->components);
	}
}