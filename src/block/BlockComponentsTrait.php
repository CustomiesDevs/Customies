<?php

namespace customiesdevs\customies\block;

use customiesdevs\customies\block\component\BlockComponent;
use customiesdevs\customies\block\component\DestructibleByMiningComponent;
use customiesdevs\customies\block\component\FrictionComponent;
use customiesdevs\customies\block\component\LightDampeningComponent;
use customiesdevs\customies\block\component\LightEmissionComponent;
use customiesdevs\customies\block\component\MaterialInstancesComponent;
use pocketmine\block\Opaque;

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
	 * @todo Work on more default values depending on different pm classes similar to items
	 * Initializes the block with default components that are required for the block to function correctly.
	 */
	protected function initComponent(string $texture): void {
		$this->addComponent(new LightEmissionComponent());
		$this->addComponent(new LightDampeningComponent());
		$this->addComponent(new DestructibleByMiningComponent());
		$this->addComponent(new FrictionComponent());
		$this->addComponent(new MaterialInstancesComponent("*", $texture));

		if($this instanceof Opaque) {
			$this->addComponent(new MaterialInstancesComponent("*", $texture, MaterialInstancesComponent::RENDER_METHOD_OPAQUE));
		}
	}
}