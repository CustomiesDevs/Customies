<?php
declare(strict_types=1);

namespace customiesdevs\customies;

use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\ClosureTask;
use pocketmine\world\format\io\WritableWorldProviderManagerEntry;

use customiesdevs\customies\{block\CustomiesBlockFactory, util\Cache};

use Closure;

final class Customies extends PluginBase {

    /**
     * @return void
     */
	protected function onLoad(): void {

		Cache::setInstance(new Cache($this->getDataFolder() . "idcache"));

	}

	/**
     * @return void
     */
	protected function onEnable(): void {
		$this->getServer()->getPluginManager()->registerEvents(new CustomiesListener(), $this);

		$cachePath = $this->getDataFolder() . "idcache";
		$this->getScheduler()->scheduleDelayedTask(new ClosureTask(static function () use ($cachePath): void {
            /**
             * This task is scheduled with a 0-tick delay, so it runs as soon as the server has started. Plugins should
             * register their custom blocks and entities in onEnable() before this is executed.
             */
			Cache::getInstance()->save();
            CustomiesBlockFactory::getInstance()->registerCustomRuntimeMappings();
            CustomiesBlockFactory::getInstance()->addWorkerInitHook($cachePath);

		}), 0);
	}
}
