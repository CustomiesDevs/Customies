<?php
declare(strict_types=1);

namespace customiesdevs\customies\block\permutations;

use pocketmine\block\utils\InvalidBlockStateException;
use function array_map;
use function count;
use function current;
use function next;
use function reset;

class Permutations {

	/**
	 * Attempts to return an array of block properties from the provided meta value based on the possible permutations
	 * of the block. An exception is thrown if the meta value does not match any combinations of all the block
	 * properties.
	 */
	public static function fromMeta(Permutable $block, int $meta): array {
		$possibleValues = array_map(static fn(BlockProperty $blockProperty) => $blockProperty->getValues(), $block->getBlockProperties());
		$properties = self::getCartesianProduct($possibleValues)[$meta] ?? null;
		if($properties === null) {
			throw new InvalidBlockStateException("Unable to calculate permutations from block meta: " . $meta);
		}
		return $properties;
	}

	/**
	 * Attempts to convert the block in to a meta value based on the possible permutations of the block. An exception is
	 * thrown if the state of the block is not a possible combination of all the block properties.
	 */
	public static function toMeta(Permutable $block): int {
		$possibleValues = array_map(static fn(BlockProperty $blockProperty) => $blockProperty->getValues(), $block->getBlockProperties());
		foreach(self::getCartesianProduct($possibleValues) as $meta => $permutations){
			if($permutations === $block->getCurrentBlockProperties()) {
				return $meta;
			}
		}
		throw new InvalidBlockStateException("Unable to calculate block meta from current permutations");
	}

	/**
	 * Returns the number of bits required to represent all the possible permutations of the block.
	 */
	public static function getStateBitmask(Permutable $block): int {
		$possibleValues = array_map(static fn(BlockProperty $blockProperty) => $blockProperty->getValues(), $block->getBlockProperties());
		return count(self::getCartesianProduct($possibleValues)) - 1;
	}

	/**
	 * Returns an 2-dimensional array containing all possible combinations of the provided arrays using the cartesian
	 * product (https://en.wikipedia.org/wiki/Cartesian_product).
	 */
	public static function getCartesianProduct(array $arrays): array {
		$result = [];
		$count = count($arrays) - 1;
		$combinations = array_product(array_map(static fn(array $array) => count($array), $arrays));
		for($i = 0; $i < $combinations; $i++){
			$result[] = array_map(static fn(array $array) => current($array), $arrays);
			for($j = $count; $j >= 0; $j--){
				if(next($arrays[$j])) {
					break;
				}
				reset($arrays[$j]);
			}
		}
		return $result;
	}
}