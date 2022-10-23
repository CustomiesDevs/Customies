<?php
declare(strict_types=1);

namespace customiesdevs\customies\item;

use customiesdevs\customies\util\NBT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\ListTag;
use RuntimeException;

trait ItemComponentsTrait {

	private CompoundTag $componentTag;

	/**
	 * Attempts to set a property with the key and value provided. It will attempt to turn the value in to a Tag, but
	 * will throw an exception if it cannot convert it.
	 */
	public function addProperty(string $key, $value): void {
		$propertiesTag = $this->componentTag->getCompoundTag("components")->getCompoundTag("item_properties");
		$tag = NBT::getTagType($value);
		if($tag === null) {
			throw new RuntimeException("Failed to get tag type for property with key " . $key);
		}
		$propertiesTag->setTag($key, $tag);
	}

	/**
	 * Attempts to set a component with the key and value provided. It will attempt to turn the value in to a Tag, but
	 * will throw an exception if it cannot convert it.
	 */
	public function addComponent(string $key, $value): void {
		$componentsTag = $this->componentTag->getCompoundTag("components");
		$tag = NBT::getTagType($value);
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

	/**
	 * When a custom item has a texture that is not 16x16, the item will scale when held in a hand based on the size of
	 * the texture. This method adds the minecraft:render_offsets component with the correct data for the provided width
	 * and height of a texture to make the item scale correctly. An optional bool for hand equipped can be used if the
	 * item is something like a tool or weapon.
	 */
	protected function setupRenderOffsets(int $width, int $height, bool $handEquipped = false): void {
		$scaleTag = CompoundTag::create()
			->setTag("scale", new ListTag([
				new FloatTag(($handEquipped ? 0.075 : 0.1) / ($width / 16)),
				new FloatTag(($handEquipped ? 0.125 : 0.1) / ($height / 16)),
				new FloatTag(($handEquipped ? 0.075 : 0.1) / ($width / 16))
			]));
		$perspectivesTag = CompoundTag::create()
			->setTag("first_person", $scaleTag)
			->setTag("third_person", $scaleTag);
		$this->addComponent("minecraft:render_offsets", CompoundTag::create()
			->setTag("main_hand", $perspectivesTag)
			->setTag("off_hand", $perspectivesTag));
	}
}
