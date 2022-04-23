<?php
declare(strict_types=1);

namespace customies\task;

use customies\block\CustomiesBlockFactory;
use pocketmine\block\Block;
use pocketmine\scheduler\AsyncTask;

class AsyncWorkerStartHookTask extends AsyncTask {

	private string $blocks;
	private string $models;

	public function __construct(string $blocks, string $models) {
		$this->blocks = $blocks;
		$this->models = $models;
	}

	public function onRun(): void {
		$blocks = unserialize($this->blocks);
		$models = unserialize($this->models);

		CustomiesBlockFactory::init();

		/**
		 * @var  $identifier string
		 * @var  $block      Block
		 */
		foreach($blocks as $identifier => $block){
			CustomiesBlockFactory::registerBlock(get_class($block), $identifier, $block->getName(), $block->getBreakInfo(), $models[$block->getId()] ?? null);
		}

		CustomiesBlockFactory::updateRuntimeMappings();
	}
}