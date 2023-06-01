<?php
declare(strict_types=1);

namespace customiesdevs\customies\task;

use customiesdevs\customies\block\CustomiesBlockFactory;
use customiesdevs\customies\util\Cache;
use pmmp\thread\ThreadSafeArray;
use pocketmine\block\Block;
use pocketmine\data\bedrock\block\convert\BlockStateReader;
use pocketmine\data\bedrock\block\convert\BlockStateWriter;
use pocketmine\scheduler\AsyncTask;

final class AsyncRegisterBlocksTask extends AsyncTask {

	private ThreadSafeArray $blockFuncs;
	private ThreadSafeArray $objectToState;
	private ThreadSafeArray $stateToObject;

	/**
	 * @param Closure[] $blockFuncs
	 * @phpstan-param array<string, array{(Closure(int): Block), (Closure(BlockStateReader): void), (Closure(BlockStateWriter): void)}> $blockFuncs
	 */
	public function __construct(private string $cachePath, array $blockFuncs) {
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
		Cache::setInstance(new Cache($this->cachePath));
		foreach($this->blockFuncs as $identifier => $blockFunc){
			// We do not care about the model or creative inventory data in other threads since it is unused outside of
			// the main thread.
			CustomiesBlockFactory::getInstance()->registerBlock($blockFunc, $identifier, stateReader: $this->objectToState[$identifier], stateWriter: $this->stateToObject[$identifier]);
		}
	}
}
