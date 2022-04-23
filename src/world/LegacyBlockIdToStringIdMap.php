<?php

namespace customies\world;

use customies\block\CustomiesBlockFactory;
use pocketmine\utils\SingletonTrait;
use const pocketmine\BEDROCK_DATA_PATH;

final class LegacyBlockIdToStringIdMap {
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
		$blockIdMap = json_decode((string)file_get_contents(BEDROCK_DATA_PATH . "block_id_map.json"), true);
		$this->stringToLegacy = array_merge($blockIdMap);
		$this->legacyToString = array_flip($this->stringToLegacy);
	}

	public function legacyToString(int $legacy): ?string {
		return $this->legacyToString[$legacy] ?? null;
	}

	public function stringToLegacy(string $string): ?int {
		return $this->stringToLegacy[$string] ?? null;
	}

	public function registerMapping(string $string, int $legacy): void {
		$this->legacyToString[$legacy] = $string;
		$this->stringToLegacy[$string] = $legacy;
	}
}