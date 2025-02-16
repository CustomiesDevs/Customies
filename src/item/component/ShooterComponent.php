<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class ShooterComponent implements ItemComponent {

	private bool $chargeOnDraw;
	private float $maxDrawDuration;
	private bool $scalePowerByDrawDuration;

	private string $item;
	private bool $useOffhand;
	private bool $searchInventory;
	private bool $useInCreative;

	/**
	 * Shooter component compels an item to shoot projectiles, similarly to a bow or crossbow. 
	 * Must have the use_modifiers component in order to function properly.
	 * @param string $item Ammunition item
	 * @param bool $useOffhand Can use off-hand? Default is set to false
	 * @param bool $searchInventory Can search inventory? Default is set to fals
	 * @param bool $useInCreative Can use in creative mode? Default is set to false
	 * @param bool $chargeOnDraw Charge on draw? Default is set to false
	 * @param float $maxDrawDuration Draw Duration. Default is set to 0
	 * @param bool $scalePowerByDrawDuration Scale power by draw duration? Default is set to false
	 */
	public function __construct(string $item, bool $useOffhand = false, bool $searchInventory = false, bool $useInCreative = false, bool $chargeOnDraw = false, float $maxDrawDuration = 0.0, bool $scalePowerByDrawDuration = false) {
		$this->item = $item;
		$this->useOffhand = $useOffhand;
		$this->searchInventory = $searchInventory;
		$this->useInCreative = $useInCreative;
		$this->chargeOnDraw = $chargeOnDraw;
		$this->maxDrawDuration = $maxDrawDuration;
		$this->scalePowerByDrawDuration = $scalePowerByDrawDuration;
	}

	public function getName(): string {
		return "minecraft:shooter";
	}

	public function getValue(): array {
		return [
			"ammunition" => [
				[
					"item" => $this->item,
					"use_offhand" => $this->useOffhand,
					"search_inventory" => $this->searchInventory,
					"use_in_creative" => $this->useInCreative
				]
			],
			"charge_on_draw" => $this->chargeOnDraw,
			"max_draw_duration" => $this->maxDrawDuration,
			"scale_power_by_draw_duration" => $this->scalePowerByDrawDuration
		];
	}

	public function isProperty(): bool {
		return false;
	}
}