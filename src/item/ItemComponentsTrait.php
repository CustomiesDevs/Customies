<?php
declare(strict_types=1);

namespace customiesdevs\customies\item;

use customiesdevs\customies\item\component\AllowOffHandComponent;
use customiesdevs\customies\item\component\ArmorComponent;
use customiesdevs\customies\item\component\CanDestroyInCreativeComponent;
use customiesdevs\customies\item\component\CooldownComponent;
use customiesdevs\customies\item\component\CreativeCategoryComponent;
use customiesdevs\customies\item\component\CreativeGroupComponent;
use customiesdevs\customies\item\component\DisplayNameComponent;
use customiesdevs\customies\item\component\DurabilityComponent;
use customiesdevs\customies\item\component\FoodComponent;
use customiesdevs\customies\item\component\FuelComponent;
use customiesdevs\customies\item\component\HandEquippedComponent;
use customiesdevs\customies\item\component\IconComponent;
use customiesdevs\customies\item\component\ItemComponent;
use customiesdevs\customies\item\component\MaxStackSizeComponent;
use customiesdevs\customies\item\component\ProjectileComponent;
use customiesdevs\customies\item\component\RenderOffsetsComponent;
use customiesdevs\customies\item\component\ThrowableComponent;
use customiesdevs\customies\item\component\UseAnimationComponent;
use customiesdevs\customies\item\component\UseDurationComponent;
use customiesdevs\customies\item\component\WearableComponent;
use customiesdevs\customies\util\NBT;
use naeng\ItemTexture\ItemTexture;
use pocketmine\entity\Consumable;
use pocketmine\inventory\ArmorInventory;
use pocketmine\item\Armor;
use pocketmine\item\Durable;
use pocketmine\item\Food;
use pocketmine\item\ProjectileItem;
use pocketmine\nbt\tag\CompoundTag;
use RuntimeException;

trait ItemComponentsTrait {

	/** @var ItemComponent[] */
	private array $components;

	public function addComponent(ItemComponent $component): void {
		$this->components[$component->getName()] = $component;
	}

	public function hasComponent(string $name): bool {
		return isset($this->components[$name]);
	}

	public function getComponents(): CompoundTag {
		$components = CompoundTag::create();
		$properties = CompoundTag::create();
		foreach($this->components as $component){
			$tag = NBT::getTagType($component->getValue());
			if($tag === null) {
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
	protected function initComponent(string $texture, ?CreativeInventoryInfo $creativeInfo = null): void {
        if(class_exists(ItemTexture::class)){
            ItemTexture::registerItemTexture($this->getTypeId(), $texture);
        }
		$creativeInfo ??= CreativeInventoryInfo::DEFAULT();
		$this->addComponent(new CreativeCategoryComponent($creativeInfo));
		$this->addComponent(new CreativeGroupComponent($creativeInfo));
		$this->addComponent(new CanDestroyInCreativeComponent());
		$this->addComponent(new IconComponent($texture));
		$this->addComponent(new MaxStackSizeComponent($this->getMaxStackSize()));

		if($this instanceof Armor) {
			$slot = match ($this->getArmorSlot()) {
				ArmorInventory::SLOT_HEAD => WearableComponent::SLOT_ARMOR_HEAD,
				ArmorInventory::SLOT_CHEST => WearableComponent::SLOT_ARMOR_CHEST,
				ArmorInventory::SLOT_LEGS => WearableComponent::SLOT_ARMOR_LEGS,
				ArmorInventory::SLOT_FEET => WearableComponent::SLOT_ARMOR_FEET,
				default => WearableComponent::SLOT_ARMOR
			};
			$this->addComponent(new ArmorComponent($this->getDefensePoints()));
			$this->addComponent(new WearableComponent($slot));
		}

		if($this instanceof Consumable) {
			if(($food = $this instanceof Food)) {
				$this->addComponent(new FoodComponent(!$this->requiresHunger()));
			}
			$this->addComponent(new UseAnimationComponent($food ? UseAnimationComponent::ANIMATION_EAT : UseAnimationComponent::ANIMATION_DRINK));
			$this->setUseDuration(20);
		}

		if($this instanceof Durable) {
			$this->addComponent(new DurabilityComponent($this->getMaxDurability()));
		}

		if($this instanceof ProjectileItem) {
			$this->addComponent(new ProjectileComponent("projectile"));
			$this->addComponent(new ThrowableComponent(true));
		}

		if($this->getName() !== "Unknown") {
			$this->addComponent(new DisplayNameComponent($this->getName()));
		}

		if($this->getFuelTime() > 0) {
			$this->addComponent(new FuelComponent($this->getFuelTime()));
		}
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

	/**
	 * Change if you want to allow the item to be placed in a player's off-hand or not. This is set to false by default,
	 * so it only needs to be set if you want to allow it.
	 */
	protected function allowOffHand(bool $offHand = true): void {
		$this->addComponent(new AllowOffHandComponent($offHand));
	}

	/**
	 * Set the number of seconds the item should be on cooldown for after being used. By default, the cooldown category
	 * will be the name of the item, but to share the cooldown across multiple items you can provide a shared category.
	 */
	protected function setUseCooldown(float $duration, string $category = ""): void {
		$this->addComponent(new CooldownComponent($category !== "" ? $category : $this->getName(), $duration));
	}

	/**
	 * Set the number of ticks the use animation should play for before consuming the item. There are 20 ticks in a
	 * second, so providing the number 20 will create a duration of 1 second.
	 */
	protected function setUseDuration(int $ticks): void {
		$this->addComponent(new UseDurationComponent($ticks));
	}
}
