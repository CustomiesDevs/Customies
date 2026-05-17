<?php
declare(strict_types=1);

namespace customiesdevs\customies\item;

use customiesdevs\customies\item\component\DisplayNameComponent;
use customiesdevs\customies\item\component\IconComponent;
use customiesdevs\customies\item\component\ItemComponent;

trait ItemComponentsTrait {

	/**
	 * Registered item components indexed by component name.
	 * 
	 * @var array<string, ItemComponent>
	 */
	private array $components;

	/**
	 * Adds a component to the item.
	 * 
	 * @param ItemComponent $component The component to add
	 */
	public function addComponent(ItemComponent $component): void {
		$this->components[$component->getName()] = $component;
	}

	/**
	 * Checks if the item has a component by its name.
	 * 
	 * @param string $name The name of the component
	 * @return bool True if the component exists, false otherwise
	 */
	public function hasComponent(string $name): bool {
		return isset($this->components[$name]);
	}

	/**
	 * Retrieves a component by its name.
	 * 
	 * @param string $name The name of the component
	 * @return ItemComponent|null The component if found, null otherwise
	 */
	public function getComponent(string $name): ?ItemComponent {
		return $this->components[$name] ?? null;
	}

	/**
	 * Returns all components of the item.
	 *
	 * @return ItemComponent[] Array of all components
	 */
	public function getComponents(): array {
		return $this->components;
	}

	/**
	 * @todo
	 * Initializes common item components.
	 * 
	 * @param string $texture The texture identifier for the icon
	 * @param string $name The display name of the item
	 */
	protected function initComponent(string $texture, string $name): void {
		// Only initialize if no components are set yet
		if($this->getComponents() !== []){
			return;
		}
		$this->addComponent(new IconComponent($texture));
		$this->addComponent(new DisplayNameComponent($name));
	}
}