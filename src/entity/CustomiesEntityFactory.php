<?php
declare(strict_types=1);

namespace customies\entity;

use pocketmine\entity\EntityDataHelper;
use pocketmine\entity\EntityFactory;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\world\World;
use function array_flip;
use function count;

class CustomiesEntityFactory {

	/**
	 * @var string[]
	 * @phpstan-var array<string, string>
	 */
	private static $identifierToClassMap = [];
	/**
	 * @var string[]
	 * @phpstan-var array<int, string>
	 */
	private static $idToIdentifierMap = [];

	/**
	 * Returns the identifier => className map for all custom entities.
	 *
	 * @return array<string, string>
	 */
	public static function getIdentifierToClassMap(): array {
		return self::$identifierToClassMap;
	}

	/**
	 * Returns a compound tag array of all the custom entity ids/identifiers.
	 * This is used to make the client know about all the entities.
	 *
	 * @return CompoundTag[]
	 */
	public static function getAvailableActorIdentifiers(): array {
		$identifiers = [];

		foreach(self::$idToIdentifierMap as $legacyId => $identifier){
			$identifiers[] = CompoundTag::create()
				->setString("bid", "")
				->setByte("experimental", 1)
				->setByte("hasspawnegg", 0)
				->setString("id", $identifier)
				->setInt("rid", $legacyId)
				->setByte("summonable", 1);
		}

		return $identifiers;
	}

	/**
	 * Returns the legacyId => identifier map for all custom entities.
	 *
	 * @return array<int, string>
	 */
	public static function getIdToIdentifierMap(): array {
		return self::$idToIdentifierMap;
	}

	/**
	 * Returns the identifier of an entity from its legacy id.
	 * An empty string is returned if the entity is not registered.
	 *
	 * @param int $id
	 *
	 * @return string
	 */
	public static function getIdentifierFromId(int $id): string {
		return self::$idToIdentifierMap[$id] ?? "";
	}

	/**
	 * Returns the legacy id of an entity from its string identifier.
	 * -1 will be returned if the entity identifier is not registered.
	 *
	 * @param string $identifier
	 *
	 * @return int
	 */
	public static function getIdFromIdentifier(string $identifier): int {
		return array_flip(self::$idToIdentifierMap)[$identifier] ?? -1;
	}

	/**
	 * Register an entity to the EntityFactory and all the required mappings.
	 *
	 * @param string $className
	 * @param string $identifier
	 *
	 * @phpstan-param class-string<\pocketmine\entity\Entity> $className
	 */
	public static function registerEntity(string $className, string $identifier): void {
		$id = 150 + count(self::$idToIdentifierMap);

		EntityFactory::getInstance()->register($className, static function (World $world, CompoundTag $nbt) use ($className): string {
			return new $className(EntityDataHelper::parseLocation($nbt, $world), $nbt);
		}, [$identifier]);

		self::$identifierToClassMap[$identifier] = $className;
		self::$idToIdentifierMap[$id] = $identifier;
	}
}