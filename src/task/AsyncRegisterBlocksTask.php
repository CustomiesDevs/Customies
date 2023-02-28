<?php
declare(strict_types=1);

namespace customiesdevs\customies\task;

use pocketmine\block\Block;
use pocketmine\scheduler\AsyncTask;

use customiesdevs\customies\{block\CustomiesBlockFactory, util\Cache};

use Closure;
use Threaded;

final class AsyncRegisterBlocksTask extends AsyncTask {

    /** @var Threaded $blockFuncs */
	private Threaded $blockFuncs;

	/**
	 * @param string $cachePath
	 * @param Closure[] $blockFuncs
	 * @phpstan-param array<string, Closure(int): Block> $blockFuncs
	 */
	public function __construct(private string $cachePath, array $blockFuncs) {

		$this->blockFuncs = new Threaded();

		foreach($blockFuncs as $identifier => $blockFunc)
            $this->blockFuncs[$identifier] = $blockFunc;
	}

	public function onRun(): void {

		Cache::setInstance(new Cache($this->cachePath));
        /**
         * We do not care about the model or creative inventory data in other threads since it is unused outside of
         * the main thread.
         */
		foreach($this->blockFuncs as $identifier => $blockFunc)
            CustomiesBlockFactory::getInstance()->registerBlock($blockFunc, $identifier);

		CustomiesBlockFactory::getInstance()->registerCustomRuntimeMappings();

	}
}
