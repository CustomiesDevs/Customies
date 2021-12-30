<?php
declare(strict_types=1);

namespace customies\task;

use Closure;
use pocketmine\scheduler\AsyncTask;

/**
 * Special thanks to Jack Noordhuis for the original implementation of this class.
 * Jack's source code: https://gist.github.com/JackNoordhuis/2be6bce14de17aee36c794af78299207.
 */
class AsyncClosureTask extends AsyncTask {

	/** @var Closure[] */
	private array $closures;

	/**
	 * @param Closure[]|Closure $closures
	 */
	public function __construct(mixed $closures) {
		if($closures instanceof Closure) {
			$closures = [$closures];
		}
		$this->closures = $closures;
	}

	public function onRun(): void {
		/** @var Closure[] $closures */
		$closures = $this->closures;
		foreach($closures as $closure){
			($closure)();
		}
	}
}