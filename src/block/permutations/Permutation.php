<?php
declare(strict_types=1);

namespace customiesdevs\customies\block\permutations;

use customiesdevs\customies\util\NBT;
use pocketmine\nbt\tag\CompoundTag;

final class Permutation
{

	private string $condition;
	private CompoundTag $components;

	public function __construct(string $condition)
	{
		$this->condition = $condition;
		$this->components = CompoundTag::create();
	}

	/**
	 * Returns the permutation with the provided component added to the current list of components.
	 */
	public function withComponent(string $component, mixed $value): self
	{
		$this->components->setTag($component, NBT::getTagType($value));
		return $this;
	}

	/**
	 * Returns the permutation in the correct NBT format supported by the client.
	 */
	public function toNBT(): CompoundTag
	{
		return CompoundTag::create()
			->setString("condition", $this->condition)
			->setTag("components", $this->components);
	}
}