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
		$blocks = unserialize($this->blocks);
		/**
		 * @var  $identifier string
		 * @var  $block      Block
		 */
		foreach($blocks as $identifier => $block){
			CustomiesBlockFactory::getInstance()->registerBlock(get_class($block), $identifier, $block->getName(), $block->getBreakInfo());
		}
		CustomiesBlockFactory::getInstance()->registerCustomRuntimeMappings();
	}
}