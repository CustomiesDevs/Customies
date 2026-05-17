<?php
declare(strict_types=1);

namespace customiesdevs\customies\block\component;

use customiesdevs\customies\block\component\BlockComponent;
use customiesdevs\customies\block\component\DisplayNameComponent;
use customiesdevs\customies\block\component\GeometryComponent;
use customiesdevs\customies\block\component\MaterialInstancesComponent;
use customiesdevs\customies\block\properties\Material;

trait BlockComponentsTrait {
	
	/**
	 * Registered block components indexed by component name.
	 * @var array<string, BlockComponent>
	 */
	private array $components;

	/**
	 * Adds or replaces a block component.
	 * If a component with the same name already exists, it will be overwritten.
	 * @param BlockComponent $component The component to add.
	 */
	public function addComponent(BlockComponent $component): void {
		$this->components[$component->getName()] = $component;
	}

	/**
	 * Checks whether the block has a component with the given name.
	 *
	 * @param string $name Component identifier (e.g. "minecraft:flammable")
	 * @return bool True if the component exists, false otherwise.
	 */
	public function hasComponent(string $name): bool {
		return isset($this->components[$name]);
	}

	/**
	 * Retrieves a component by its name.
	 *
	 * @param string $name Component identifier.
	 * @return BlockComponent|null The component if present, otherwise null.
	 */
	public function getComponent(string $name): ?BlockComponent {
		return $this->components[$name] ?? null;
	}

	/**
	 * Returns all registered block components.
	 *
	 * @return array<string, BlockComponent>
	 */
	public function getComponents(): array {
		return $this->components;
	}

	/**
	 * @todo
	 * Initializes the default components for a block with the given texture and name.
	 * Adds geometry, material instances, and display name components.
	 * 
	 * @param string $texture The texture identifier
	 * @param string $name The display name of the block
	 */
	protected function initComponents(string $texture, string $name): void {
		// Only initialize if no components are set yet
		if($this->getComponents() !== []){
			return;
		}
		$this->addComponent(new GeometryComponent());
		$this->addComponent(new MaterialInstancesComponent([new Material("*", $texture)]));
		$this->addComponent(new DisplayNameComponent($name));
	}
}