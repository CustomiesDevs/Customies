<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class DisplayNameComponent implements ItemComponent {

	private string $name;

	/**
	 * Sets the item display name within Minecraft: Bedrock Edition. 
	 * This component may also be used to pull from the localization file by referencing a key from it. 
	 * @param string $name Name shown for an item
	 */
	public function __construct(string $name) {
		$this->name = $name;
	}

	public function getName(): string {
		return "minecraft:display_name";
	}

	public function getValue(): array {
		return [
			"value" => $this->name
		];
	}

	public function isProperty(): bool {
		return false;
	}
}