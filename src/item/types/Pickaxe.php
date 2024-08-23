<?php

declare(strict_types=1);

namespace customiesdevs\customies\item\types;

use customiesdevs\customies\item\component\MiningSpeedComponent;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\Pickaxe as PM_Pickaxe;

class Pickaxe extends PM_Pickaxe {
	
	/* Example:
	*  public function __construct(ItemIdentifier $identifier, string $name = "Unknown") {
	*	 parent::__construct($identifier, $name, \pocketmine\item\ToolTier::NETHERITE());
	*	 $this->addComponent(new MiningSpeedComponent($this->getTypePickaxe(), $this->getMiningEfficiency(true)));
	*  }
	*/

	public function getTypePickaxe(): string {
		return "wood";
	}
	
	public function updateEfficiency(): void{
		$this->removeMiningSpeedComponent();
		$this->addComponent(new MiningSpeedComponent($this->getTypePickaxe(), $this->getMiningEfficiency(true)));
	}

	#[Override]
	public function addEnchantment(EnchantmentInstance $enchantment) : self{
		$this->enchantments[spl_object_id($enchantment->getType())] = $enchantment;
		$this->updateEfficiency();
		return $this;
	}

	#[Override]
	public function removeEnchantment(Enchantment $enchantment, int $level = -1) : self{
		$instance = $this->getEnchantment($enchantment);
		if($instance !== null && ($level === -1 || $instance->getLevel() === $level)){
			unset($this->enchantments[spl_object_id($enchantment)]);
		}
		$this->updateEfficiency();

		return $this;
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
