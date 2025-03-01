<?php

namespace customiesdevs\customies\block;

use customiesdevs\customies\block\component\BlockComponent;
use customiesdevs\customies\block\component\CollisionBoxComponent;
use customiesdevs\customies\block\component\DestructibleByMiningComponent;
use customiesdevs\customies\block\component\DisplayNameComponent;
use customiesdevs\customies\block\component\FlammableComponent;
use customiesdevs\customies\block\component\FrictionComponent;
use customiesdevs\customies\block\component\GeometryComponent;
use customiesdevs\customies\block\component\LightDampeningComponent;
use customiesdevs\customies\block\component\LightEmissionComponent;
use customiesdevs\customies\block\component\MaterialInstancesComponent;
use customiesdevs\customies\block\component\SelectionBoxComponent;

trait BlockComponentsTrait {
	
	/** @var BlockComponent[] */
	private array $components;

	public function addComponent(BlockComponent $component): void {
		$this->components[$component->getName()] = $component;
	}

	public function hasComponent(string $name): bool {
		return isset($this->components[$name]);
	}

	/**
	 * @return array
	 */
	public function getComponents(): array {
		return $this->components;
	}

	/** 
	 * This Creates a Normal Block Functions
	 * Initializes the block with default components that are required for the block to function correctly
	 * @todo Work on more default values depending on different pm classes similar to items
	 */
	protected function initComponent(string $texture, bool $useGeometry = true): void {
		$this->addComponent(new LightEmissionComponent($this->getLightLevel()));
		$this->addComponent(new LightDampeningComponent($this->getLightFilter()));
		$this->addComponent(new DestructibleByMiningComponent($this->getBreakInfo()->getHardness()));
		$this->addComponent(new FrictionComponent($this->getFrictionFactor()));
		if ($useGeometry){
			$this->addComponent(new GeometryComponent()); // if there's no geometry then set it as a full block
		}
		$this->addComponent(new SelectionBoxComponent());
		if($this->hasEntityCollision()){	
			$this->addComponent(new CollisionBoxComponent());
		}
		if($this->getFlammability() > 0){
			$this->addComponent(new FlammableComponent($this->getFlameEncouragement()));
		}
		if($this->getName() !== "Unknown") {
			$this->addComponent(new DisplayNameComponent($this->getName()));
		}
		$this->addComponent(new MaterialInstancesComponent([new Material(Material::TARGET_ALL, $texture, Material::RENDER_METHOD_OPAQUE)]));
	}
}