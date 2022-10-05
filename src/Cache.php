<?php
declare(strict_types=1);

namespace customiesdevs\customies\util;

use pocketmine\utils\Filesystem;
use pocketmine\utils\SingletonTrait;
use function file_exists;
use function file_get_contents;
use function gzcompress;
use function gzuncompress;
use function igbinary_serialize;
use function igbinary_unserialize;

final class Cache {
	use SingletonTrait;

	private const FIRST_BLOCK_ID = 1000;
	private const FIRST_ITEM_ID = 950;

	/**
	 * @var array<string, int>
	 */
	private array $itemCache = [];

	/**
	 * @var array<string, int>
	 */
	private array $blockCache = [];
	private string $file;

	public function __construct(string $cacheFile) {
		$this->file = $cacheFile;
		if(file_exists($cacheFile)) {
			$data = igbinary_unserialize(gzuncompress(file_get_contents($cacheFile)));
			$this->blockCache = $data["blocks"];
			$this->itemCache = $data["items"];
		}
	}

	/**
	 * Returns the next available block id.
	 */
	public function getNextAvailableBlockID(string $identifier) {
		return $this->getNextAvailableID($identifier, $this->blockCache, self::FIRST_BLOCK_ID);
	}

	/**
	 * Returns the next available item id.
	 */
	public function getNextAvailableItemID(string $identifier) {
		return $this->getNextAvailableID($identifier, $this->itemCache, self::FIRST_ITEM_ID);
	}

	/**
	 * Returns the lowest valid id for that specific identifier if it hasn't already been cached. It
	 * will then cache it. If the identifier has been cached it will return the associated cached numeric id.
	 */
	private function getNextAvailableID(string $identifier, array &$cache, int $startID): int {
		// if it's cached we just return the already cached id.
		if(isset($cache[$identifier])) {
			return $cache[$identifier];
		}

		$id = null;
		if(count($cache) > 1) {
			// make use of empty sections
			// flip the array to have numeric ids as keys -- faster for isset
			$flipped = array_flip($cache);
			// go through every number to find any empty sections
			for($i = array_key_first($flipped)+1, $iMax = array_key_last($flipped); $i < $iMax; $i++){
				if(!isset($flipped[$i])) {
					$id = $i;
					break;
				}
			}
		}
		$cache[$identifier] = ($id ??= $startID);
		return $id;
	}

	/**
	 * Flushes the cache to disk in the appropriate format.
	 */
	public function save(): void {
		$data = ["items" => $this->itemCache, "blocks" => $this->blockCache];
		Filesystem::safeFilePutContents($this->file, gzcompress(igbinary_serialize($data)));
	}
}
