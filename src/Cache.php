<?php
declare(strict_types=1);

namespace customiesdevs\customies\util;

use pocketmine\utils\Filesystem;
use pocketmine\utils\SingletonTrait;
use function asort;
use function file_exists;
use function file_get_contents;
use function gzcompress;
use function gzuncompress;
use function igbinary_serialize;
use function igbinary_unserialize;

final class Cache {
    use SingletonTrait;

	private int $nextBlockID;
	private int $nextItemID;
	private string $file;

	/**
	 * @var array<string, int>
	 */
	private array $itemCache = [];

	/**
	 * @var array<string, int>
	 */
	private array $blockCache = [];

	public function __construct(string $cacheFile) {
		$this->nextBlockID = 1000;
		$this->nextItemID = 950;
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
	public function getNextAvailableBlockID(string $identifier){
		return $this->getNextAvailableID($identifier, $this->blockCache, $this->nextBlockID);
	}

	/**
	 * Returns the next available item id.
	 */
	public function getNextAvailableItemID(string $identifier){
		return $this->getNextAvailableID($identifier, $this->itemCache, $this->nextItemID);
	}

	/**
	 * Returns the lowest valid if for that specific identifier it hasn't already been cached. It
	 * will then cache it. If the identifier has been cached it will return the associated cached numeric id.
	 */
	private function getNextAvailableID(string $identifier, array &$cache, int &$nextID): int {
		// if it's cached then we just return the already cached id.
		if(isset($cache[$identifier])) {
			return $cache[$identifier];
		}
		$id = ++$nextID;
		$previous = null;
		foreach($cache as $key => $value){
			if($value > $id) {
				if($previous !== null) {
					$id = $cache[$previous] + 1;
					// if the id already exists increment by one and keep looking
					if($id === $value) {
						$id += 1;
						continue;
					}
					$cache[$identifier] = $id;
					break;
				}
				$nextID = $id;
			}
			$previous = $key;
		}
		// we do this on the off chance that the id matches the greatest id inside of the cache.
		if($cache[$previous] === $id) {
			$id += 1;
			$cache[$identifier] = $id;
			$nextID = $id;
		}
		asort($cache);
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
