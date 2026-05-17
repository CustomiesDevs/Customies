<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class HoverTextColorComponent implements ItemComponent {

	private string $hoverTextColor;

	/** @var array<string, string> */
	private const COLOR_CODE_MAP = [
		'black' => '§0',
		'dark_blue' => '§1',
		'dark_green' => '§2',
		'dark_aqua' => '§3',
		'dark_red' => '§4',
		'dark_purple' => '§5',
		'gold' => '§6',
		'gray' => '§7',
		'dark_gray' => '§8',
		'blue' => '§9',
		'green' => '§a',
		'aqua' => '§b',
		'red' => '§c',
		'light_purple' => '§d',
		'yellow' => '§e',
		'white' => '§f',
		'minecoin_gold' => '§g',
		'material_quartz' => '§h',
		'material_iron' => '§i',
		'material_netherite' => '§j',
		'material_redstone' => '§m',
		'material_copper' => '§n',
		'material_gold' => '§p',
		'material_emerald' => '§q',
		'material_diamond' => '§s',
		'material_lapis' => '§t',
		'material_amethyst' => '§u',
		'material_resin' => '§v',
		'party_blue_color' => '§w',
	];

	/**
	 * Determines the color of the item name when hovering over it.
	 * @param string $hoverTextColor Specifies the color of the item's hover text
	 * @link [ColorCodes](https://minecraft.wiki/w/Formatting_codes#Color_codes)
	 */
	public function __construct(string $hoverTextColor) {
		$this->hoverTextColor = $hoverTextColor;
	}

	public function getName(): string {
		return 'minecraft:hover_text_color';
	}

	public function getValue(): array {
		return [
			"value" => $this->hoverTextColor
		];
	}

	public function getPropertyMapping(): ?array {
		return ['hover_text_color' => $this->convertToColorCode($this->hoverTextColor)];
	}

	private function convertToColorCode(string $color): string {
		return self::COLOR_CODE_MAP[$color] ?? $color;
	}
}