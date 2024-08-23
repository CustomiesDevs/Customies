<?php

declare(strict_types=1);

namespace customiesdevs\customies\item\types;

use pocketmine\item\Pickaxe as PM_Pickaxe;

class Pickaxe extends PM_Pickaxe {

	public function __construct(ItemIdentifier $identifier, string $name = "Unknown") {
		parent::__construct($identifier, $name, \pocketmine\item\ToolTier::NETHERITE());
		$this->addComponent(new MiningSpeedComponent("diamond", $this->getMiningEfficiency(true)));
	}
	
	public function updateEfficiency(int $upLV): void{
		$this->removeMiningSpeedComponent();
		$this->addComponent(new MiningSpeedComponent("diamond", $this->getMiningEfficiency(true)));
	}
	
        #[Override]
        public function getMiningEfficiency(bool $isCorrectTool) : float{
		$efficiency = 1;
		if($isCorrectTool){
			$efficiency = $this->getBaseMiningEfficiency();
			if(($enchantmentLevel = $this->getEnchantmentLevel(VanillaEnchantments::EFFICIENCY())) > 0){
				$efficiency += ($enchantmentLevel ** 2 + 1);
			}
		}

		return $efficiency;
	}
        
        #[Override]
	protected function getBaseMiningEfficiency() : float{
		return 1;
        }
}
