<?php

namespace customiesdevs\customies\Items\Components;

use customiesdevs\customies\Items\Components\ArmorComponent;use customiesdevs\customies\Items\Components\CanDestroyInCreativeComponent;use customiesdevs\customies\Items\Components\DisplayNameComponent;use customiesdevs\customies\Items\Components\DurabilityComponent;use customiesdevs\customies\Items\Components\FoodComponent;use customiesdevs\customies\Items\Components\FuelComponent;use customiesdevs\customies\Items\Components\HandEquippedComponent;use customiesdevs\customies\Items\Components\IconComponent;use customiesdevs\customies\Items\Components\ItemComponentBase;use customiesdevs\customies\Items\Components\MaxStackSizeComponent;use customiesdevs\customies\Items\Components\ProjectileComponent;use customiesdevs\customies\Items\Components\ThrowableComponent;use customiesdevs\customies\Items\Components\UseAnimationComponent;use customiesdevs\customies\Items\Components\WearableComponent;use customiesdevs\customies\Utils\NBT;
use pocketmine\entity\Consumable;
use pocketmine\item\Axe;
use pocketmine\item\Durable;
use pocketmine\item\Food;
use pocketmine\item\Hoe;
use pocketmine\item\Pickaxe;
use pocketmine\item\ProjectileItem;
use pocketmine\item\Shovel;
use pocketmine\item\Sword;
use pocketmine\nbt\tag\CompoundTag;
use customiesdevs\customies\Items\CustomArmor;
use customiesdevs\customies\Utils\CreativeInventoryInfo;
use customiesdevs\customies\Utils\CreativeItemManager;
use RuntimeException;

trait ItemComponentsTrait {

    /** @var ItemComponentBase[] */
    private array $components;

    public function addComponent(ItemComponentBase $component): void {
        $this->components[$component->getName()] = $component;
    }

    /**
     * @param array $components
     */
    public function hasComponent(ItemComponentBase|string $component): bool
    {
        return isset($this->components[$component->getName()]);
    }

    public function getComponents(): CompoundTag
    {
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

    public function initComponent(string $texture, CreativeInventoryInfo $creativeInventoryInfo): void
    {
        CreativeItemManager::getInstance()->AddItemOnGroup($this, $creativeInventoryInfo);
        $this->addComponent(new IconComponent($texture));
        $this->addComponent(new DisplayNameComponent($this->getName()));
        $count = $this->getMaxStackSize();
        $this->addComponent(new MaxStackSizeComponent($count));
        $this->addComponent(new CanDestroyInCreativeComponent(true));


        //NE PAS TOUCHER !!!
        if (
            $this instanceof Axe ||
            $this instanceof Pickaxe ||
            $this instanceof Shovel ||
            $this instanceof Hoe
        ) {
            $this->addComponent(new HandEquippedComponent(true));
        }
        if ($this instanceof Sword) {
            $this->addComponent(new CanDestroyInCreativeComponent(false));
            $this->addComponent(new HandEquippedComponent(true));
        }
        if ($this instanceof CustomArmor) {
            $this->addComponent(new ArmorComponent($this->getDefensePoints()));
            $this->addComponent(new WearableComponent($this->getArmor(), $this->getDefensePoints(), $this->getToughness()));
        }

        if ($this instanceof Durable) {
            $this->addComponent(new DurabilityComponent($this->getMaxDurability()));
        }

        if (!$this->getFuelTime() <= 0) {
            $this->addComponent(new FuelComponent($this->getFuelTime()));
        }

        if($this instanceof ProjectileItem) {
            $this->addComponent(new ProjectileComponent("projectile"));
            $this->addComponent(new ThrowableComponent(true));
        }

        if($this instanceof Consumable) {
            if(($food = $this instanceof Food)) {
                $this->addComponent(new FoodComponent(!$this->requiresHunger()));
            }
            $this->addComponent(new UseAnimationComponent($food ? UseAnimationComponent::ANIMATION_EAT : UseAnimationComponent::ANIMATION_DRINK));
        }
    }
}