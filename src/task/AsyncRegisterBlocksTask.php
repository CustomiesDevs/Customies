<?php
declare(strict_types=1);

namespace customiesdevs\customies\task;

use pocketmine\block\Block;
use pocketmine\scheduler\AsyncTask;

use customiesdevs\customies\{block\CustomiesBlockFactory, util\Cache};

use Closure;
use ReflectionException;
use ThreadedArray;

final class AsyncRegisterBlocksTask extends AsyncTask
{

	/** @var ThreadedArray $blockFuncs */
	private ThreadedArray $blockFuncs;

	/** @var ThreadedArray $objectToState */
	private ThreadedArray $objectToState;

	/** @var ThreadedArray $stateToObject */
	private ThreadedArray $stateToObject;

	/**
	 * @param string $cachePath
	 * @param Closure[] $blockFuncs
	 * @phpstan-param array<string, Closure(int): Block> $blockFuncs
	 */
	public function __construct(private readonly string $cachePath, array $blockFuncs)
	{

		$this->blockFuncs = new ThreadedArray();
		$this->objectToState = new ThreadedArray();
		$this->stateToObject = new ThreadedArray();

		foreach($blockFuncs as $identifier => [$blockFunc, $objectToState, $stateToObject]) {

			$this->blockFuncs[$identifier] = $blockFunc;
			$this->objectToState[$identifier] = $objectToState;
			$this->stateToObject[$identifier] = $stateToObject;

		}
	}

	/**
	 * @throws ReflectionException
	 */
	public function onRun(): void
	{

		Cache::setInstance(new Cache($this->cachePath));
		/**
		 * We do not care about the model or creative inventory data in other threads since it is unused outside
		 *  the main thread.
		 */
		foreach ($this->blockFuncs as $identifier => $blockFunc)
			CustomiesBlockFactory::getInstance()->registerBlock($blockFunc, $identifier, objectToState: $this->objectToState[$identifier], stateToObject: $this->stateToObject[$identifier]);

	}
}
