<?php
declare(strict_types=1);

namespace customiesdevs\customies\util;

use pocketmine\utils\Filesystem;
use function asort;
use function file_exists;
use function file_get_contents;
use function gzcompress;
use function gzuncompress;
use function igbinary_serialize;
use function igbinary_unserialize;

final class IDCache {

	private int $nextID;
	private string $file;

	/**
	 * @var array
	 */
	private array $cache = [];

	public function __construct(int $startingID, string $cacheFile) {
		$this->nextID = $startingID;
		$this->file = $cacheFile;
		if(file_exists($cacheFile)) {
			$this->cache = igbinary_unserialize(gzuncompress(file_get_contents($cacheFile)));
		}
	}

	/**
	 * Returns the lowest valid if for that specific identifier it hasn't already been cached. It
	 * will then cache it. If the identifier has been cached it will return the associated cached numeric id.
	 */
	public function getNextAvailableID(string $identifier): int {
		// if it's cached then we just return the already cached id.
		if(isset($this->cache[$identifier])) {
			return $this->cache[$identifier];
		}
		$id = ++$this->nextID;
		$previous = null;
		foreach($this->cache as $key => $value){
			if($value > $id) {
				if($previous !== null) {
					$id = $this->cache[$previous] + 1;
					// if the id already exists increment by one and keep looking
					if($id === $value) {
						$id += 1;
						continue;
					}
					$this->cache[$identifier] = $id;
					break;
				}
				$this->nextID = $id;
			}
			$previous = $key;
		}
		// we do this on the off chance that the id matches the greatest id inside of the cache.
		if($this->cache[$previous] === $id) {
			$id += 1;
			$this->cache[$identifier] = $id;
			$this->nextID = $id;
		}
		asort($this->cache);
		return $id;
	}

	/**
	 * Flushes the cache to disk in the appropriate format.
	 */
	public function save(): void {
		Filesystem::safeFilePutContents($this->file, gzcompress(igbinary_serialize($this->cache)));
	}
}