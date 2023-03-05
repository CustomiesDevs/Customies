<?php
declare(strict_types=1);

namespace customiesdevs\customies\task;

use Closure;
use customiesdevs\customies\{block\CustomiesBlockFactory, util\Cache};
use pocketmine\block\Block;
use pocketmine\scheduler\AsyncTask;
use ReflectionException;
use Threaded;

final class AsyncRegisterBlocksTask extends AsyncTask
{

	/** @var Threaded $blockFuncs */
	private Threaded $blockFuncs;

	/** @var Threaded $objectToState */
	private Threaded $objectToState;

	/** @var Threaded $stateToObject */
	private Threaded $stateToObject;

	/**
	 * @param string $cachePath
	 * @param Closure[] $blockFuncs
	 * @phpstan-param array<string, Closure(int): Block> $blockFuncs
	 */
	public function __construct(private readonly string $cachePath, array $blockFuncs)
	{

		$this->blockFuncs = new Threaded();
		$this->objectToState = new Threaded();
		$this->stateToObject = new Threaded();

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
