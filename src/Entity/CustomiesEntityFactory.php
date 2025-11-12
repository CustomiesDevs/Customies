<?php

namespace customiesdevs\customies\Entity;

use Closure;
use pocketmine\entity\Entity;
use pocketmine\entity\EntityDataHelper;
use pocketmine\entity\EntityFactory;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\network\mcpe\cache\StaticPacketCache;
use pocketmine\network\mcpe\protocol\AvailableActorIdentifiersPacket;
use pocketmine\network\mcpe\protocol\types\CacheableNbt;
use pocketmine\utils\SingletonTrait;
use pocketmine\world\World;
use ReflectionClass;

class CustomiesEntityFactory {
    use SingletonTrait;

    public array $entities = [];

    private static int $ID = 400;

    public function registerEntity(string $entityClass, ?Closure $customClosure = null, bool $isCustomEntity = true) : void
    {
        $identifier = $entityClass::getNetworkTypeId();
        $customClosure ??= function (World $world, CompoundTag $nbt) use ($entityClass) : Entity {
            return new $entityClass(EntityDataHelper::parseLocation($nbt, $world), $nbt);
        };
        EntityFactory::getInstance()->register($entityClass, $customClosure, [$identifier]);
        if($isCustomEntity){
            $this->registerAvailableActorIdentifiers($identifier);
        }
    }

    public function registerAvailableActorIdentifiers(string $networkId) : void{
        StaticPacketCache::getInstance()->getAvailableActorIdentifiers()->identifiers->getRoot()->getListTag("idlist")->push(CompoundTag::create()
            ->setByte("hasspawnegg", 1)
            ->setString("id", $networkId)
            ->setInt("rid", self::$ID++)
            ->setByte("summonable", 1));
    }
}