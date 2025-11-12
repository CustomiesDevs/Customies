<?php

namespace customiesdevs\customies\Blocks;

use customiesdevs\customies\Blocks\Components\BlockComponent;
use customiesdevs\customies\Blocks\Components\BreathabilityComponent;
use customiesdevs\customies\Blocks\Components\CollisionBoxComponent;
use customiesdevs\customies\Blocks\Components\DestructibleByExplosionComponent;
use customiesdevs\customies\Blocks\Components\DestructibleByMiningComponent;
use customiesdevs\customies\Blocks\Components\DisplayNameComponent;
use customiesdevs\customies\Blocks\Components\FlammableComponent;
use customiesdevs\customies\Blocks\Components\FrictionComponent;
use customiesdevs\customies\Blocks\Components\GeometryComponent;
use customiesdevs\customies\Blocks\Components\LightDampeningComponent;
use customiesdevs\customies\Blocks\Components\LightEmissionComponent;
use customiesdevs\customies\Blocks\Components\MaterialInstancesComponent;
use customiesdevs\customies\Blocks\Components\SelectionBoxComponent;

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
	 * @return BlockComponent[]
	 */
	public function getComponents(): array {
		return $this->components;
	}

	/** 
	 * Initialises a block's components with default values inferred from existing properties.
	 * @todo Work on more default values depending on different pm classes similar to items
	 * @param string $texture Texture name for the material.
	 * @param bool $useGeometry Check if geometry component should be used, default is set to `true`
	 */
	protected function initComponent(string $texture, bool $useGeometry = true): void {
		$this->addComponent(new BreathabilityComponent());
		$this->addComponent(new DestructibleByExplosionComponent());
		$this->addComponent(new DestructibleByMiningComponent($this->getBreakInfo()->getHardness()));
		$this->addComponent(new LightEmissionComponent($this->getLightLevel()));
		$this->addComponent(new LightDampeningComponent($this->getLightFilter()));
		$this->addComponent(new FrictionComponent($this->getFrictionFactor()));
		if ($useGeometry){
			$this->addComponent(new GeometryComponent());
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