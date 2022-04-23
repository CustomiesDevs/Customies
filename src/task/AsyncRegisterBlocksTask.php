<?php
declare(strict_types=1);

namespace customies\task;

use customies\block\CustomiesBlockFactory;
use pocketmine\block\Block;
use pocketmine\scheduler\AsyncTask;

final class AsyncRegisterBlocksTask extends AsyncTask {

	private string $blocks;

	public function __construct(string $blocks) {
		$this->blocks = $blocks;
	}

	public function onRun(): void {
		/** @phpstan-var array<string, Block> $blocks */
		$blocks = unserialize($this->blocks);
		foreach($blocks as $identifier => $block){
			/** @phpstan-var class-string $className */
			$className = get_class($block);
			CustomiesBlockFactory::getInstance()->registerBlock($className, $identifier, $block->getName(), $block->getBreakInfo());
		}
		CustomiesBlockFactory::getInstance()->registerCustomRuntimeMappings();
	}
}