<?php
declare(strict_types=1);

namespace twistedasylummc\customies\block;

use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\convert\RuntimeBlockMapping;
use pocketmine\utils\SingletonTrait;
use ReflectionClass;
use ReflectionProperty;
use RuntimeException;

final class BlockPalette {
	use SingletonTrait;

	/** @var CompoundTag[] */
	private array $states;
	/** @var CompoundTag[] */
	private array $customStates = [];

	private RuntimeBlockMapping $runtimeBlockMapping;
	private ReflectionProperty $bedrockKnownStates;

	public function __construct() {
		$this->runtimeBlockMapping = $instance = RuntimeBlockMapping::getInstance();
		$this->states = $instance->getBedrockKnownStates();
		$runtimeBlockMapping = new ReflectionClass($instance);
		$this->bedrockKnownStates = $bedrockKnownStates = $runtimeBlockMapping->getProperty("bedrockKnownStates");
		$bedrockKnownStates->setAccessible(true);
	}

	/**
	 * @return CompoundTag[]
	 */
	public function getStates(): array {
		return $this->states;
	}

	/**
	 * @return CompoundTag[]
	 */
	public function getCustomStates(): array {
		return $this->customStates;
	}

	/**
	 * Inserts the provided state in to the correct position of the palette.
	 */
	public function insertState(CompoundTag $state): void {
		if($state->getString("name" . "") === "") {
			throw new RuntimeException("Block state must contain a StringTag called 'name'");
		}
		if($state->getCompoundTag("states") === null) {
			throw new RuntimeException("Block state must contain a CompoundTag called 'states'");
		}
		$this->sortWith($state);
		$this->customStates[] = $state;
	}

	/**
	 * Sorts the palette's block states in the correct order, also adding the provided state to the array.
	 */
	private function sortWith(CompoundTag $state): void {
		$states = [$state->getString("name") => [$state]];
		foreach($this->states as $state){
			$states[$state->getString("name")][] = $state;
		}
		$names = array_keys($states);
		usort($names, static fn(string $a, string $b) => strcmp(hash("fnv164", $a), hash("fnv164", $b)));
		$sortedStates = [];
		foreach($names as $name){
			foreach($states[$name] as $state){
				$sortedStates[] = $state;
			}
		}
		$this->states = $sortedStates;
		$this->bedrockKnownStates->setValue($this->runtimeBlockMapping, $sortedStates);
	}
}