<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\properties;

use pocketmine\nbt\tag\ShortTag;

final class RepairAmount {

	/** 
	 * @param int|float|null $numeric Numeric repair amount
	 * @param string|null $expression Molang expression for repair amount
	 * example:
	 * ```php
	 * $repairAmount1 = RepairAmount::numeric(10); // Numeric repair amount of 10
	 * $repairAmount2 = RepairAmount::molang("query.max_durability * 0.2"); // Molang expression for 20% of max durability
	 * ```
	 */
	private function __construct(
		private readonly int|float|null $numeric,
		private readonly ?string $expression,
	) {}

	/** Create a numeric repair amount */
	public static function numeric(int|float $value): self {
		return new self($value, null);
	}

	/** Create a Molang-based repair amount */
	public static function molang(string $expression = "query.max_durability * 0.25"): self {
		return new self(null, $expression);
	}

	/**
	 * Returns Bedrock-compatible representation
	 *
	 * @return int|float|array{
	 *   expression: string,
	 *   version: ShortTag
	 * }
	 */
	public function toArray(): int|float|array {
		if($this->numeric !== null){
			return $this->numeric;
		}
		return [
			"expression" => $this->expression,
			"version" => new ShortTag(13)
		];
	}
}
