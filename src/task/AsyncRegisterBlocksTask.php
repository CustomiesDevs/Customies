<?php
declare(strict_types=1);

namespace customiesdevs\customies\task;

use customiesdevs\customies\block\CustomiesBlockFactory;
use pocketmine\block\Block;
use pocketmine\scheduler\AsyncTask;
use pmmp\thread\ThreadSafeArray;

final class AsyncRegisterBlocksTask extends AsyncTask {

	private ThreadSafeArray $blockFuncs;
	private ThreadSafeArray $objectToState;
	private ThreadSafeArray $stateToObject;

	/**
	 * @param Closure[] $blockFuncs
	 * @phpstan-param array<string, Closure(int): Block> $blockFuncs
	 */
	public function __construct(array $blockFuncs) {
		$this->blockFuncs = new ThreadSafeArray();
		$this->objectToState = new ThreadSafeArray();
		$this->stateToObject = new ThreadSafeArray();

		foreach($blockFuncs as $identifier => [$blockFunc, $objectToState, $stateToObject]){
			$this->blockFuncs[$identifier] = $blockFunc;
			$this->objectToState[$identifier] = $objectToState;
			$this->stateToObject[$identifier] = $stateToObject;
		}
	}

	public function onRun(): void {
		foreach($this->blockFuncs as $identifier => $blockFunc){
			// We do not care about the model or creative inventory data in other threads since it is unused outside of
			// the main thread.
			CustomiesBlockFactory::getInstance()->registerBlock($blockFunc, $identifier, objectToState: $this->objectToState[$identifier], stateToObject: $this->stateToObject[$identifier]);
		}
	}
}
