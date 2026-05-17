<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class IconComponent implements ItemComponent {

	private string $default_texture;
	private string $dyed_texture;
	private string $trim_texture;
	private string $bundle_open_back_texture;
	private string $bundle_open_front_texture;

	/**
	 * Determines the icon to represent the item in the UI and elsewhere.
	 * @param string $default_texture the texture name should same as the `resource_pack/textures/item_texture.json` `texture_data`
	 * @param string $dyed_texture Default is set to `None`
	 * @param string $trim_texture Default is set to `None`
	 * @param string $bundle_open_back_texture Default is set to `None`
	 * @param string $bundle_open_front_texture Default is set to `None`
	 */
	public function __construct(
		string $default_texture, 
		string $dyed_texture = "", 
		string $trim_texture = "", 
		string $bundle_open_back_texture = "", 
		string $bundle_open_front_texture = ""
	) {
		$this->default_texture = $default_texture;
		$this->dyed_texture = $dyed_texture;
		$this->trim_texture = $trim_texture;
		$this->bundle_open_back_texture = $bundle_open_back_texture;
		$this->bundle_open_front_texture = $bundle_open_front_texture;
	}

	public function getName(): string {
		return 'minecraft:icon';
	}

	public function getValue(): array {
		$textures = ["default" => $this->default_texture];
		if($this->dyed_texture !== "") $textures["dyed"] = $this->dyed_texture;
		if($this->trim_texture !== "") $textures["icon_trim"] = $this->trim_texture;
		if($this->bundle_open_back_texture !== "") $textures["bundle_open_back"] = $this->bundle_open_back_texture;
		if($this->bundle_open_front_texture !== "") $textures["bundle_open_front"] = $this->bundle_open_front_texture;
		return ["textures" => $textures];
	}

	public function getPropertyMapping(): ?array {
		return null;
	}
}