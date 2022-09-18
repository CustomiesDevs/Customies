<?php
declare(strict_types=1);

namespace customiesdevs\customies;

use Closure;
use customiesdevs\customies\block\CustomiesBlockFactory;
use customiesdevs\customies\item\CustomiesItemFactory;
use customiesdevs\customies\world\LevelDB;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\ClosureTask;
use pocketmine\utils\Config;
use pocketmine\world\format\io\WritableWorldProviderManagerEntry;

final class Customies extends PluginBase {

    private Config $item_id_config;
    private Config $block_id_config;

	protected function onLoad(): void {
	    if($this->getConfig()->get("item-id-caching", false)){
	        $this->item_id_config = new Config($this->getDataFolder() . "item_id_cache.json");
	        CustomiesItemFactory::getInstance()->initCache($this->item_id_config->getAll());
        }
	    if($this->getConfig()->get("block-id-caching", false)){
	        $this->block_id_config = new Config($this->getDataFolder() . "block_id_cache.json");
	        CustomiesBlockFactory::getInstance()->initCache($this->block_id_config->getAll());
        }
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
        if($this->item_id_config !== null){
            $this->item_id_config->setAll(CustomiesItemFactory::getInstance()->getItemIdCache());
            $this->saveResource("item_id_cache.json");
        }
        if($this->block_id_config !== null){
            $this->block_id_config->setAll(CustomiesBlockFactory::getInstance()->getBlockIdCache());
            $this->saveResource("block_id_cache.json");
        }
    }
}
