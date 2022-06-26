<?php
declare(strict_types=1);

namespace customiesdevs\customies\item;

use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\nbt\tag\Tag;
use RuntimeException;

trait ItemComponentsTrait {

	private CompoundTag $componentTag;

	/**
	 * Attempts to set a property with the key and value provided. It will attempt to turn the value in to a Tag, but
	 * will throw an exception if it cannot convert it.
	 */
	public function addProperty(string $key, $value): void {
		$propertiesTag = $this->componentTag->getCompoundTag("components")->getCompoundTag("item_properties");
		$tag = $this->getTagType($value);
		if($tag === null) {
			throw new RuntimeException("Failed to get tag type for property with key " . $key);
		}
		$propertiesTag->setTag($key, $tag);
	}

	/**
	 * Attempts to return the correct Tag for the provided type.
	 */
	private function getTagType($type): ?Tag {
		return match (true) {
			is_array($type) => new ListTag($type),
			is_bool($type) => new ByteTag($type ? 1 : 0),
			is_float($type) => new FloatTag($type),
			is_int($type) => new IntTag($type),
			is_string($type) => new StringTag($type),
			$type instanceof CompoundTag => $type,
			default => null,
		};
	}

	/**
	 * Attempts to set a component with the key and value provided. It will attempt to turn the value in to a Tag, but
	 * will throw an exception if it cannot convert it.
	 */
	public function addComponent(string $key, $value): void {
		$componentsTag = $this->componentTag->getCompoundTag("components");
		$tag = $this->getTagType($value);
		if($tag === null) {
			throw new RuntimeException("Failed to get tag type for component with key " . $key);
		}
		$componentsTag->setTag($key, $tag);
	}

	public function getComponents(): CompoundTag {
		return $this->componentTag;
	}

	/**
	 * Initializes the components and creates the base CompoundTag required for the components to be sent to a client.
	 * This must be called before any properties or components are added otherwise it will break.
	 */
	protected function initComponent(string $texture, int $maxStackSize, ?CreativeInventoryInfo $creativeInfo = null): void {
		$creativeInfo ??= CreativeInventoryInfo::DEFAULT();
		$this->componentTag = CompoundTag::create()
			->setTag("components", CompoundTag::create()
				->setTag("item_properties", CompoundTag::create()
					->setInt("creative_category", $creativeInfo->getNumericCategory())
					->setString("creative_group", $creativeInfo->getGroup())
					->setTag("minecraft:icon", CompoundTag::create()
						->setString("texture", $texture))
					->setInt("max_stack_size", $maxStackSize)));
	}
}
