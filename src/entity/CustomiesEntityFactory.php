<?php
declare(strict_types=1);

namespace customiesdevs\customies\entity;

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

	/**
	 * Register an entity to the EntityFactory and all the required mappings.
	 * @phpstan-param class-string<Entity> $className
	 * @phpstan-param Closure(World $world, CompoundTag $nbt) : Entity $creationFunc
	 */
	public function registerEntity(string $className, string $identifier, ?Closure $creationFunc = null): void {
		EntityFactory::getInstance()->register($className, $creationFunc ?? static function (World $world, CompoundTag $nbt) use ($className): Entity {
				return new $className(EntityDataHelper::parseLocation($nbt, $world), $nbt);
			}, [$identifier]);
		$this->updateStaticPacketCache($identifier);
	}

	public function updateStaticPacketCache(string $identifier): void {
		$instance = StaticPacketCache::getInstance();
		$staticPacketCache = new ReflectionClass($instance);
		$property = $staticPacketCache->getProperty("availableActorIdentifiers");
		$property->setAccessible(true);
		/** @var AvailableActorIdentifiersPacket $packet */
		$packet = $property->getValue($instance);
		/** @var CompoundTag $root */
		$root = $packet->identifiers->getRoot();
		$idList = $root->getListTag("idlist") ?? new ListTag();
		$idList->push(CompoundTag::create()->setString("id", $identifier));
		$packet->identifiers = new CacheableNbt($root);
	}
}
