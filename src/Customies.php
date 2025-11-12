<?php
declare(strict_types=1);

namespace customiesdevs\customies;

use customiesdevs\customies\Blocks\CustomiesBlockFactory;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\ClosureTask;

final class Customies extends PluginBase {

	protected function onEnable(): void {
		$this->getServer()->getPluginManager()->registerEvents(new CustomiesListener(), $this);

		$cachePath = $this->getDataFolder() . "idcache";
		$this->getScheduler()->scheduleDelayedTask(new ClosureTask(static function () use ($cachePath): void {
			CustomiesBlockFactory::getInstance()->addWorkerInitHook($cachePath);
		}), 0);
	}
}
