<?php
declare(strict_types=1);

namespace customiesdevs\customies;

use customiesdevs\customies\block\CustomiesBlockFactory;
use customiesdevs\customies\commands\GiveItem;
use customiesdevs\customies\util\Cache;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\ClosureTask;
use pocketmine\Server;

final class Customies extends PluginBase {

	protected function onLoad(): void {
		Cache::setInstance(new Cache($this->getDataFolder() . "idcache", true));
	}

	protected function onEnable(): void {
		$this->getServer()->getPluginManager()->registerEvents(new CustomiesListener(), $this);

		$cachePath = $this->getDataFolder() . "idcache";
		$this->getScheduler()->scheduleDelayedTask(new ClosureTask(static function () use ($cachePath): void {
			// This task is scheduled with a 0-tick delay so it runs as soon as the server has started. Plugins should
			// register their custom blocks and entities in onEnable() before this is executed.
			Cache::getInstance()->save();
			CustomiesBlockFactory::getInstance()->addWorkerInitHook($cachePath);
		}), 0);
        Server::getInstance()->getCommandMap()->register("customies", new GiveItem());
	}
}
