<?php
declare(strict_types=1);

namespace customiesdevs\customies\task;

use customiesdevs\customies\block\CustomiesBlockFactory;
use pocketmine\block\Block;
use pocketmine\scheduler\AsyncTask;

final class AsyncRegisterBlocksTask extends AsyncTask {

	public function __construct(private string $blocks) {
	}

	public function onRun(): void {
		/** @phpstan-var array<string, Closure(int): Block> $blocks */
		$blocks = unserialize($this->blocks);
		foreach($blocks as $identifier => $blockFunc){
			// We do not care about the model or creative inventory data in other threads since it is unused outside of
			// the main thread.
			CustomiesBlockFactory::getInstance()->registerBlock($blockFunc, $identifier);
		}
		CustomiesBlockFactory::getInstance()->registerCustomRuntimeMappings();
	}
}
