<?php

namespace customiesdevs\customies;

use pocketmine\block\VanillaBlocks;
use pocketmine\entity\animation\ArmSwingAnimation;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\world\sound\FireExtinguishSound;

class CustomiesPlayer extends Player {

    public function getDisplayName(): string
    {
        $name = $this->displayName;
        if ($this->hasSpace($name)){
            $name = str_replace(" ", "_", $name);
            $this->displayName = $name;
            $this->setDisplayName($name);
            return $name;
        }

        return $name;
    }

    public function getName(): string
    {
        $name = $this->username;
        if ($this->hasSpace($name)){
            $name = str_replace(" ", "_", $name);
            return $name;
        }

        return $name;
    }

    public function hasSpace(string $string): bool
    {
        return str_contains($string, ' ');
    }

    protected ?SurvivalBlockBreakHandler $blockBreakHandlerCustom = null;

    public function attackBlock(Vector3 $pos, int|Facing $face): bool {
        if ($pos->distanceSquared($this->location) > 10000) {
            return false;
        }

        $target = $this->getWorld()->getBlock($pos);

        $ev = new PlayerInteractEvent($this, $this->getInventory()->getItemInHand(), $target, null, $face, PlayerInteractEvent::LEFT_CLICK_BLOCK);

        if ($this->isSpectator()) {
            $ev->cancel();
        }

        $ev->call();
        if ($ev->isCancelled()) {
            return false;
        }
        $this->broadcastAnimation(new ArmSwingAnimation($this), $this->getViewers());

        if ($target->onAttack($this->getInventory()->getItemInHand(), $face, $this)) {
            return true;
        }

        $block = $target->getSide($face);
        if ($block->getTypeId() === VanillaBlocks::FIRE()->getTypeId()) {
            $this->getWorld()->setBlock($block->getPosition(), VanillaBlocks::AIR());
            $this->getWorld()->addSound($block->getPosition()->add(0.5, 0.5, 0.5), new FireExtinguishSound());
            return true;
        }

        if (!$this->isCreative() || !$block->getBreakInfo()->breaksInstantly()) {
            $this->blockBreakHandlerCustom = new SurvivalBlockBreakHandler($this, $pos, $target, $face, 16);
        }

        return true;
    }

    public function continueBreakBlock(Vector3 $pos, int $face): void {
        if ($this->blockBreakHandlerCustom !== null && $this->blockBreakHandlerCustom->getBlockPos()->distanceSquared($pos) < 0.0001) {
            $this->blockBreakHandlerCustom->setTargetedFace($face);
            if (($this->blockBreakHandlerCustom->getBreakProgress() + $this->blockBreakHandlerCustom->getBreakSpeed()) >= 0.80) {
                $pos = $this->blockBreakHandlerCustom->getBlockPos();
                $this->breakBlock($pos);
            }
        }
    }

    public function stopBreakBlock(Vector3 $pos): void {
        if ($this->blockBreakHandlerCustom !== null && $this->blockBreakHandlerCustom->getBlockPos()->distanceSquared($pos) < 0.0001) {
            $this->blockBreakHandlerCustom = null;
        } else parent::stopBreakBlock($pos);
    }

    public function onUpdate(int $currentTick): bool {
        if ($this->blockBreakHandlerCustom !== null && !$this->blockBreakHandlerCustom->update()) {
            $this->blockBreakHandlerCustom = null;
        }
        return parent::onUpdate($currentTick);
    }
}