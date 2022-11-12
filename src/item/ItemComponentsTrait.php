<?php
declare(strict_types=1);

namespace customiesdevs\customies\item;

use customiesdevs\customies\item\component\CreativeCategoryComponent;
use customiesdevs\customies\item\component\CreativeGroupComponent;
use customiesdevs\customies\item\component\HandEquippedComponent;
use customiesdevs\customies\item\component\IconComponent;
use customiesdevs\customies\item\component\ItemComponent;
use customiesdevs\customies\item\component\MaxStackSizeComponent;
use customiesdevs\customies\item\component\RenderOffsetsComponent;
use customiesdevs\customies\util\NBT;
use pocketmine\nbt\tag\CompoundTag;
use RuntimeException;

trait ItemComponentsTrait {

	/** @var ItemComponent[] */
	private array $components;

	public function addComponent(ItemComponent $component): void {
		$this->components[$component->getName()] = $component;
	}

	public function hasComponent(string $name): bool {
		return isset($this->components, $name);
	}

	public function getComponents(): CompoundTag {
		$components = CompoundTag::create();
		$properties = CompoundTag::create();
		foreach($this->components as $component) {
			$tag = NBT::getTagType($component->getValue());
			if ($tag === null) {
				throw new RuntimeException("Failed to get tag type for component " . $component->getName());
			}
			if($component->isProperty()) {
				$properties->setTag($component->getName(), $tag);
				continue;
			}
			$components->setTag($component->getName(), $tag);
		}
		$components->setTag("item_properties", $properties);
		return CompoundTag::create()
			->setTag("components", $components);
	}

	/**
	 * Initializes the item with default components that are required for the item to function correctly.
	 */
	protected function initComponent(string $texture, int $maxStackSize, ?CreativeInventoryInfo $creativeInfo = null): void {
		$creativeInfo ??= CreativeInventoryInfo::DEFAULT();
		$this->addComponent(new CreativeCategoryComponent($creativeInfo));
		$this->addComponent(new CreativeGroupComponent($creativeInfo));
		$this->addComponent(new IconComponent($texture));
		$this->addComponent(new MaxStackSizeComponent($maxStackSize));
	}

	/**
	 * When a custom item has a texture that is not 16x16, the item will scale when held in a hand based on the size of
	 * the texture. This method adds the minecraft:render_offsets component with the correct data for the provided width
	 * and height of a texture to make the item scale correctly. An optional bool for hand equipped can be used if the
	 * item is something like a tool or weapon.
	 */
	protected function setupRenderOffsets(int $width, int $height, bool $handEquipped = false): void {
		$this->addComponent(new HandEquippedComponent($handEquipped));
		$this->addComponent(new RenderOffsetsComponent($width, $height, $handEquipped));
	}
}
