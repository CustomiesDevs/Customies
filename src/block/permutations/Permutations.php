<?php
declare(strict_types=1);

namespace customiesdevs\customies\block\permutations;

use function array_map;
use function array_product;
use function count;
use function current;
use function next;
use function reset;

class Permutations {

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