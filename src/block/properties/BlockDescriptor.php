<?php
declare(strict_types=1);

namespace customiesdevs\customies\block\properties;

/**
 * Describes a block reference for placement filtering.
 *
 * Can reference blocks by:
 * - Name
 * - Name + states
 * - Molang tag query
 */
final class BlockDescriptor {

	/** @var string|null */
	private ?string $name;
	/** @var array<int, array{state: string, type: int, value: mixed}> */
	private array $states = [];
	/** @var string|null */
	private ?string $tags;

	/**
	 * @param string|null $name Block identifier (e.g. minecraft:dirt)
	 * @param array<int, array{state: string, type: int, value: mixed}> $states
	 * @param string|null $tags Molang tag query
	 */
	public function __construct(
		?string $name = null,
		array $states = [],
		?string $tags = null
	) {
		$this->name = $name;
		$this->states = $states;
		$this->tags = $tags;
	}

	/**
	 * Converts descriptor to Bedrock format.
	 */
	public function toArray(): array {
		$data = [];
		if($this->name !== null){
			$data["name"] = $this->name;
		}
		if(!empty($this->states)){
			$data["states"] = $this->states;
		}
		if($this->tags !== null){
			$data["tags"] = $this->tags;
			$data["tags_version"] = (int) 13;
		}
		return $data;
	}

	/**
	 * Creates a BlockDescriptor from an array.
	 */
	public static function fromArray(array $data): self {
		return new self(
			$data["name"] ?? null,
			$data["states"] ?? [],
			$data["tags"] ?? null
		);
	}
}