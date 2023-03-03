<?php
declare(strict_types=1);

namespace customiesdevs\customies\block\permutations;

use pocketmine\nbt\tag\{CompoundTag, ListTag};

use customiesdevs\customies\util\NBT;

use function array_map;

final class BlockProperty
{

	public function __construct(private readonly string $name, private readonly array $values) {}

	/**
	 * Returns the name of the block property provided in the constructor.
	 *
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * Returns the array of possible values of the block property provided in the constructor.
	 *
	 * @return array
	 */
	public function getValues(): array
	{
		return $this->values;
	}

	/**
	 * Returns the block property in the correct NBT format supported by the client.
	 */
	public function toNBT(): CompoundTag
	{
		$values = array_map(static fn($value) => NBT::getTagType($value), $this->values);
		return CompoundTag::create()
			->setString("name", $this->name)
			->setTag("enum", new ListTag($values));
	}
}