<?php
declare(strict_types=1);

namespace customiesdevs\customies\util;

use pocketmine\utils\{Filesystem, SingletonTrait};
use function file_exists;
use function file_get_contents;
use function gzcompress;
use function gzuncompress;
use function igbinary_serialize;
use function igbinary_unserialize;

final class Cache
{

	use SingletonTrait;

	private const FIRST_BLOCK_ID = 1000;
	private const FIRST_ITEM_ID = 950;

	/** @var array<string, int> */
	private array $itemCache = [];

	/** @var array<string, int> $blockCache */
	private array $blockCache = [];


	/**
	 * @param string $cacheFile
	 */
	public function __construct(private readonly string $cacheFile)
	{
		if (file_exists($cacheFile)) {

			$data = igbinary_unserialize(gzuncompress(file_get_contents($cacheFile)));
			$this->blockCache = $data["blocks"];
			$this->itemCache = $data["items"];

		}
	}

	/**
	 * Returns the next available block id.
	 */
	public function getNextAvailableBlockID(string $identifier): int
	{
		return $this->getNextAvailableID($identifier, $this->blockCache, self::FIRST_BLOCK_ID);
	}

	/**
	 * Returns the next available item id.
	 */
	public function getNextAvailableItemID(string $identifier): int
	{
		return $this->getNextAvailableID($identifier, $this->itemCache, self::FIRST_ITEM_ID);
	}

	/**
	 * Returns the lowest valid id for that specific identifier if it hasn't already been cached. It
	 * will then cache it. If the identifier has been cached it will return the associated cached numeric id.
	 *
	 * @param string $identifier
	 * @param array $cache
	 * @param int $startID
	 * @return int
	 */
	private function getNextAvailableID(string $identifier, array &$cache, int $startID): int
	{
		// If the ID is cached, return it.
		if (isset($cache[$identifier]))
			return $cache[$identifier];

		$id = null;

		if (count($cache) > 0) {
			/**
			 * To make use of the empty sections in the cache, we need to find the lowest available id.
			 * Flip the array to have numeric ids as keys.
			 */
			$flipped = array_flip($cache);

			// Go through every number to find any empty ID.
			for ($i = array_key_first($flipped) + 1, $iMax = array_key_last($flipped) + 1; $i <= $iMax; $i++) {
				if (!isset($flipped[$i])) {
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
	 *
	 * @return void
	 */
	public function save(): void
	{
		Filesystem::safeFilePutContents($this->cacheFile, gzcompress(igbinary_serialize(["items" => $this->itemCache, "blocks" => $this->blockCache])));
	}
}
