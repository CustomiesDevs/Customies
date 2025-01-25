<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class IconComponent implements ItemComponent {

	private string $default_texture;
	private string $dyed_texture;
	private string $trim_texture;

	/**
	 * Determines the icon to represent the item in the UI and elsewhere.
	 * @param string $default_texture the texture name should same as the `resource_pack/textures/item_texture.json` `texture_data`
	 * @param string $dyed_texture Default is set to `None`
	 * @param string $trim_texture Default is set to `None`
	 */
	public function __construct(string $default_texture, string $dyed_texture = "", string $trim_texture = "") {
		$this->default_texture = $default_texture;
		$this->dyed_texture = $dyed_texture;
		$this->trim_texture = $trim_texture;
	}

	public function getName(): string {
		return "minecraft:icon";
	}

	public function getValue(): array {
		return [
			"texture" => $this->default_texture,
			"textures" => [
				"default" => $this->default_texture,
				"dyed" => $this->dyed_texture,
				"icon_trim" => $this->trim_texture
			]
		];
	}

	public function isProperty(): bool {
		return true;
	}
}