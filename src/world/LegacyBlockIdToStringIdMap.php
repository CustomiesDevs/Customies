<?php

namespace customiesdevs\customies\world;

use pocketmine\utils\SingletonTrait;
use const pocketmine\PATH;

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
		/** @phpstan-var array<string, int> $blockIdMap */
		$blockIdMap = json_decode((string)file_get_contents(PATH . "vendor/pocketmine/bedrock-block-upgrade-schema/block_legacy_id_map.json"), true);
		$this->stringToLegacy = $blockIdMap;
		/** @phpstan-var array<int, string> $flipped */
		$flipped = array_flip($this->stringToLegacy);
		$this->legacyToString = $flipped;
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
