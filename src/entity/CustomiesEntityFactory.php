<?php
declare(strict_types=1);

namespace customies\entity;

use pocketmine\entity\Entity;
use pocketmine\entity\EntityDataHelper;
use pocketmine\entity\EntityFactory;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\utils\SingletonTrait;
use pocketmine\world\World;
use function array_flip;
use function count;

class CustomiesEntityFactory {
	use SingletonTrait;

	/**
	 * @var string[]
	 * @phpstan-var array<string, string>
	 */
	private array $identifierToClassMap = [];
	/**
	 * @var string[]
	 * @phpstan-var array<int, string>
	 */
	private array $idToIdentifierMap = [];

	/**
	 * Returns the identifier => className map for all custom entities.
	 *
	 * @return array<string, string>
	 */
	public function getIdentifierToClassMap(): array {
		return $this->identifierToClassMap;
	}

	/**
	 * Returns a compound tag array of all the custom entity ids/identifiers.
	 * This is used to make the client know about all the entities.
	 *
	 * @return CompoundTag[]
	 */
	public function getAvailableActorIdentifiers(): array {
		$identifiers = [];

		foreach($this->idToIdentifierMap as $legacyId => $identifier){
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
	public function getIdToIdentifierMap(): array {
		return $this->idToIdentifierMap;
	}

	/**
	 * Returns the identifier of an entity from its legacy id.
	 * An empty string is returned if the entity is not registered.
	 *
	 * @param int $id
	 *
	 * @return string
	 */
	public function getIdentifierFromId(int $id): string {
		return $this->idToIdentifierMap[$id] ?? "";
	}

	/**
	 * Returns the legacy id of an entity from its string identifier.
	 * -1 will be returned if the entity identifier is not registered.
	 *
	 * @param string $identifier
	 *
	 * @return int
	 */
	public function getIdFromIdentifier(string $identifier): int {
		return array_flip($this->idToIdentifierMap)[$identifier] ?? -1;
	}

	/**
	 * Register an entity to the EntityFactory and all the required mappings.
	 *
	 * @param string $className
	 * @param string $identifier
	 *
	 * @phpstan-param class-string<Entity> $className
	 */
	public function registerEntity(string $className, string $identifier): void {
		$id = 150 + count($this->idToIdentifierMap);

		EntityFactory::getInstance()->register($className, static function (World $world, CompoundTag $nbt) use ($className): string {
			return new $className(EntityDataHelper::parseLocation($nbt, $world), $nbt);
		}, [$identifier]);

		$this->identifierToClassMap[$identifier] = $className;
		$this->idToIdentifierMap[$id] = $identifier;
	}
}