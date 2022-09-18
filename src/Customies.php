<?php
declare(strict_types=1);

namespace customiesdevs\customies;

use Closure;
use customiesdevs\customies\block\CustomiesBlockFactory;
use customiesdevs\customies\item\CustomiesItemFactory;
use customiesdevs\customies\world\LevelDB;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\ClosureTask;
use pocketmine\world\format\io\WritableWorldProviderManagerEntry;

final class Customies extends PluginBase {


	protected function onLoad(): void {
	    CustomiesItemFactory::getInstance()->initCache($this->getDataFolder());
	    CustomiesBlockFactory::getInstance()->initCache($this->getDataFolder());
		$provider = new WritableWorldProviderManagerEntry(\Closure::fromCallable([LevelDB::class, 'isValid']), fn(string $path) => new LevelDB($path), Closure::fromCallable([LevelDB::class, 'generate']));
		$this->getServer()->getWorldManager()->getProviderManager()->addProvider($provider, "leveldb", true);
		$this->getServer()->getWorldManager()->getProviderManager()->setDefault($provider);
	}

	protected function onEnable(): void {
		$this->getServer()->getPluginManager()->registerEvents(new CustomiesListener(), $this);

		$this->getScheduler()->scheduleDelayedTask(new ClosureTask(static function (): void {
			// This task is scheduled with a 0-tick delay so it runs as soon as the server has started. Plugins should
			// register their custom blocks and entities in onEnable() before this is executed.
			CustomiesBlockFactory::getInstance()->registerCustomRuntimeMappings();
			CustomiesBlockFactory::getInstance()->addWorkerInitHook();
		}), 0);
	}

	protected function onDisable(): void
    {
        CustomiesItemFactory::getInstance()->getItemIDCache()->save();
        CustomiesBlockFactory::getInstance()->getBlockIdCache()->save();
    }
}
