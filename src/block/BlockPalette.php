<?php
declare(strict_types=1);

namespace customiesdevs\customies\block;

use pocketmine\network\mcpe\convert\{BlockStateDictionary, BlockStateDictionaryEntry, BlockStateLookupCache, RuntimeBlockMapping};
use pocketmine\data\bedrock\block\BlockStateData;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\utils\SingletonTrait;

use ReflectionException;
use ReflectionProperty;
use RuntimeException;

use function array_map;

final class BlockPalette {

	use SingletonTrait;

	/** @var BlockStateDictionaryEntry[] */
	private array $states;

	/** @var BlockStateDictionaryEntry[]  */
	private array $customStates = [];

	/** @var BlockStateDictionary $dictionary */
	private BlockStateDictionary $dictionary;

	/** @var ReflectionProperty $bedrockKnownStates */
	private ReflectionProperty $bedrockKnownStates;

	/** @var ReflectionProperty $lookupCache */
	private ReflectionProperty $lookupCache;

	/**
	 * @throws ReflectionException
	 */
	public function __construct() {
		$instance = RuntimeBlockMapping::getInstance();
		$this->dictionary = $dictionary = $instance->getBlockStateDictionary();
		$this->states = $dictionary->getStates();

		$this->bedrockKnownStates = $bedrockKnownStates = new ReflectionProperty($dictionary, "states");
		$bedrockKnownStates->setAccessible(true);
		$this->lookupCache = $lookupCache = new ReflectionProperty($dictionary, "stateDataToStateIdLookupCache");
		$lookupCache->setAccessible(true);
	}

	/**
	 * @return BlockStateDictionaryEntry[]
	 */
	public function getStates(): array {
		return $this->states;
	}

	/**
	 * @return BlockStateDictionaryEntry[]
	 */
	public function getCustomStates(): array {
		return $this->customStates;
	}

	/**
	 * Inserts the provided state in to the correct position of the palette.
	 *
	 * @param CompoundTag $state
	 * @param int $meta
	 * @return void
	 */
	public function insertState(CompoundTag $state, int $meta = 0): void {
		if($state->getString("name") === "")
			throw new RuntimeException("Block state must contain a StringTag called 'name'");
		if($state->getCompoundTag("states") === null)
			throw new RuntimeException("Block state must contain a CompoundTag called 'states'");

		$this->sortWith($entry = new BlockStateDictionaryEntry(BlockStateData::fromNbt($state), $meta));
		$this->customStates[] = $entry;

	}

	/**
	 * Sorts the palette's block states in the correct order, also adding the provided state to the array.
	 */
	private function sortWith(BlockStateDictionaryEntry $newState): void {
		// To sort the block palette we first have to split the palette up in to groups of states. We only want to sort
		// using the name of the block, and keeping the order of the existing states.
		$states = [];
		foreach($this->getStates() as $state)
			$states[$state->getStateData()->getName()][] = $state;
		// Append the new state we are sorting with at the end to preserve existing order.
		$states[$newState->getStateData()->getName()][] = $newState;

		$names = array_keys($states);
		// As of 1.18.30, blocks are sorted using a fnv164 hash of their names.
		usort($names, static fn(string $a, string $b) => strcmp(hash("fnv164", $a), hash("fnv164", $b)));
		$sortedStates = [];
		foreach($names as $name)
			// With the sorted list of names, we can now go back and add all the states for each block in the correct order.
			foreach($states[$name] as $state)
				$sortedStates[] = $state;

		$this->states = $sortedStates;
		$this->bedrockKnownStates->setValue($this->dictionary, $sortedStates);
		$this->lookupCache->setValue(
			$this->dictionary,
			new BlockStateLookupCache(array_map(fn(BlockStateDictionaryEntry $entry) => $entry->getStateData(), $this->states))
		);
	}
}
