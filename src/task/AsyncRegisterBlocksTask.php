<?php
declare(strict_types=1);

namespace customiesdevs\customies\task;

use Closure;
use customiesdevs\customies\block\CustomiesBlockFactory;
use pocketmine\block\Block;
use pocketmine\scheduler\AsyncTask;
use Threaded;

final class AsyncRegisterBlocksTask extends AsyncTask {

	private Threaded $blockFuncs;

	/**
	 * @param Closure[] $blockFuncs
	 * @phpstan-param array<string, Closure(int): Block> $blockFuncs
	 */
	public function __construct(array $blockFuncs) {
		$this->blockFuncs = new Threaded();
		foreach($blockFuncs as $identifier => $blockFunc){
			$this->blockFuncs[$identifier] = $blockFunc;
		}
	}

	public function onRun(): void {
		foreach($this->blockFuncs as $identifier => $blockFunc){
			// We do not care about the model or creative inventory data in other threads since it is unused outside of
			// the main thread.
			CustomiesBlockFactory::getInstance()->registerBlock($blockFunc, $identifier);
		}
		CustomiesBlockFactory::getInstance()->registerCustomRuntimeMappings();
	}
}
