<?php
declare(strict_types=1);

namespace customiesdevs\customies\util;

use pocketmine\block\BlockTypeIds;
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


	/** @var array<string, int> */
	private array $blockCache = [];
	private string $file;

	public function __construct(string $cacheFile, private readonly bool $mainThread = false) {
		$this->file = $cacheFile;
		if(file_exists($cacheFile) && !$mainThread) {
			$data = igbinary_unserialize(gzuncompress(file_get_contents($cacheFile)));
			$this->blockCache = $data["blocks"];
		}
	}

	/**
	 * Returns the next available block id.
	 */
	public function getNextAvailableBlockID(string $identifier): int{
		return $this->blockCache[$identifier] ??= ($this->mainThread ? BlockTypeIds::newId() : throw new \RuntimeException("Block ID should be registered in the main thread"));
	}

	/**
	 * Flushes the cache to disk in the appropriate format.
	 */
	public function save(): void {
		$data = ["blocks" => $this->blockCache];
		Filesystem::safeFilePutContents($this->file, gzcompress(igbinary_serialize($data)));
	}

	public function isMainThread() : bool{
		return $this->mainThread;
	}
}
