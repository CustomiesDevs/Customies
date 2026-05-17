<?php
declare(strict_types=1);

namespace customiesdevs\customies\task;

use Closure;
use customiesdevs\customies\block\CustomiesBlockFactory;
use pmmp\thread\ThreadSafeArray;
use pocketmine\block\Block;
use pocketmine\data\bedrock\block\convert\BlockStateReader;
use pocketmine\data\bedrock\block\convert\BlockStateWriter;
use pocketmine\scheduler\AsyncTask;

final class AsyncRegisterBlocksTask extends AsyncTask {

	private ThreadSafeArray $blockFuncs;
	private ThreadSafeArray $serializer;
	private ThreadSafeArray $deserializer;

	/**
	 * @param Closure[] $blockFuncs Array of callbacks used for block registration
	 * @phpstan-param array<string, array{
	 *     (Closure(int): Block),
	 *     (Closure(BlockStateWriter): Block),
	 *     (Closure(Block): BlockStateReader)
	 * }> $blockFuncs Associative array where the key is the block identifier and the value is a triple of:
	 * - block creation function
	 * - serializer function
	 * - deserializer function
	 */
	public function __construct(array $blockFuncs) {
		$this->blockFuncs = new ThreadSafeArray();
		$this->serializer = new ThreadSafeArray();
		$this->deserializer = new ThreadSafeArray();
		foreach($blockFuncs as $identifier => [$blockFunc, $serializer, $deserializer]){
			$this->blockFuncs[$identifier] = $blockFunc;
			$this->serializer[$identifier] = $serializer;
			$this->deserializer[$identifier] = $deserializer;
		}
	}

	public function onRun(): void {
		foreach($this->blockFuncs as $identifier => $blockFunc){
			CustomiesBlockFactory::getInstance()->registerBlock(
				$blockFunc,
				(string) $identifier,
				serializer: $this->serializer[$identifier],
				deserializer: $this->deserializer[$identifier]
			);
		}
	}
}