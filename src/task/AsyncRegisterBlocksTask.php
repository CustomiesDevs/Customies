<?php
declare(strict_types=1);

namespace customiesdevs\customies\task;

use customiesdevs\customies\block\CustomiesBlockFactory;
use customiesdevs\customies\util\Cache;
use pocketmine\block\Block;
use pocketmine\scheduler\AsyncTask;
use ThreadedArray;

final class AsyncRegisterBlocksTask extends AsyncTask {

	private ThreadedArray $blockFuncs;
	private ThreadedArray $objectToState;
	private ThreadedArray $stateToObject;

	/**
	 * @param string $cachePath
	 * @param Closure[] $blockFuncs
	 * @phpstan-param array<string, Closure(int): Block> $blockFuncs
	 */
	public function __construct(private readonly string $cachePath, array $blockFuncs) {
		$this->blockFuncs = new ThreadedArray();
		$this->objectToState = new ThreadedArray();
		$this->stateToObject = new ThreadedArray();

		foreach($blockFuncs as $identifier => [$blockFunc, $objectToState, $stateToObject]){
			$this->blockFuncs[$identifier] = $blockFunc;
			$this->objectToState[$identifier] = $objectToState;
			$this->stateToObject[$identifier] = $stateToObject;
		}
	}

	public function onRun(): void {
		Cache::setInstance(new Cache($this->cachePath));
		foreach($this->blockFuncs as $identifier => $blockFunc){
			// We do not care about the model or creative inventory data in other threads since it is unused outside of
			// the main thread.
			CustomiesBlockFactory::getInstance()->registerBlock($blockFunc, $identifier, objectToState: $this->objectToState[$identifier], stateToObject: $this->stateToObject[$identifier]);
		}
	}
}
