<?php

namespace customies\world;

use customies\block\CustomiesBlockFactory;
use pocketmine\utils\SingletonTrait;
use const pocketmine\BEDROCK_DATA_PATH;

class LegacyBlockIdToStringIdMap {
	use SingletonTrait;

	/**
	 * @var string[]
	 * @phpstan-var array<int, string>
	 */
	private array $legacyToString;
	/**
	 * @var int[]
	 * @phpstan-var array<string, int>
	 */
	private array $stringToLegacy;

	public function __construct() {
		$blockIdMap = json_decode((string)file_get_contents(BEDROCK_DATA_PATH . 'block_id_map.json'), true);
		$this->stringToLegacy = array_merge($blockIdMap, CustomiesBlockFactory::getIdentifierToIdMap());
		$this->legacyToString = array_flip($this->stringToLegacy);
	}

	public function legacyToString(int $legacy) : ?string{
		return $this->legacyToString[$legacy] ?? null;
	}

	public function stringToLegacy(string $string) : ?int{
		return $this->stringToLegacy[$string] ?? null;
	}

	/**
	 * @return string[]
	 * @phpstan-return array<int, string>
	 */
	public function getLegacyToStringMap() : array{
		return $this->legacyToString;
	}

	/**
	 * @return int[]
	 * @phpstan-return array<string, int>
	 */
	public function getStringToLegacyMap() : array{
		return $this->stringToLegacy;
	}
}