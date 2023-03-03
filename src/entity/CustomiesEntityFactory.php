<?php
declare(strict_types=1);

namespace customiesdevs\customies\entity;

use Closure;
use pocketmine\entity\{Entity, EntityDataHelper, EntityFactory};
use pocketmine\nbt\tag\{CompoundTag, ListTag};
use pocketmine\network\mcpe\{cache\StaticPacketCache,
	protocol\AvailableActorIdentifiersPacket,
	protocol\types\CacheableNbt};
use pocketmine\utils\SingletonTrait;
use pocketmine\world\World;
use ReflectionClass;
use ReflectionException;

class CustomiesEntityFactory
{

	use SingletonTrait;

	/**
	 * Register an entity to the EntityFactory and all the required mappings. An optional behaviour identifier can be
	 * provided if you want to have your entity behave like a vanilla entity.
	 *
	 * @param string $className
	 * @param string $identifier
	 * @param Closure|null $creationFunc
	 * @param string $behaviourId
	 * @return void
	 *
	 * @phpstan-param class-string<Entity> $className
	 * @phpstan-param Closure(World $world, CompoundTag $nbt) : Entity $creationFunc
	 * @throws ReflectionException
	 */
	public function registerEntity(string $className, string $identifier, ?Closure $creationFunc = null, string $behaviourId = ""): void
	{

		EntityFactory::getInstance()->register($className, $creationFunc ?? static function (World $world, CompoundTag $nbt) use ($className): Entity {
			return new $className(EntityDataHelper::parseLocation($nbt, $world), $nbt);
		}, [$identifier]);

		$this->updateStaticPacketCache($identifier, $behaviourId);
	}

	/**
	 * @param string $identifier
	 * @param string $behaviourId
	 * @return void
	 * @throws ReflectionException
	 */
	private function updateStaticPacketCache(string $identifier, string $behaviourId): void
	{

		$instance = StaticPacketCache::getInstance();

		$property = (new ReflectionClass($instance))->getProperty("availableActorIdentifiers");
		$property->setAccessible(true);

		/** @var AvailableActorIdentifiersPacket $packet */
		$packet = $property->getValue($instance);
		/** @var CompoundTag $root */
		$root = $packet->identifiers->getRoot();

		($root->getListTag("idlist") ?? new ListTag())->push(CompoundTag::create()
			->setString("id", $identifier)
			->setString("bid", $behaviourId));

		$packet->identifiers = new CacheableNbt($root);

	}
}
