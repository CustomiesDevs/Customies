<?php
declare(strict_types=1);

namespace customiesdevs\customies\block\states;

/**
 * Represents a block state property with a name and array of possible values.
 * Automatically detects the value type (bool, int, string) for serialization.
 */
class BlockState {

	/** @var mixed The current value of the state property */
	protected mixed $currentValue;

	/**
	 * @param string $name The state property name (e.g., "customies:rotation")
	 * @param array $values Array of possible values (bool[], int[], or string[])
	 */
	public function __construct(
		protected readonly string $name,
		protected readonly array $values
	) {
		$this->currentValue = $values[0] ?? null;
	}

	/**
	 * Returns the state property name.
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * Returns the possible values array.
	 * @return array
	 */
	public function getValues(): array {
		return $this->values;
	}

	/**
	 * Sets the current value of the state property.
	 * Validates that the value is within the allowed values array.
	 * @param mixed $value The value to set (must be in $this->values)
	 * @throws \InvalidArgumentException if the value is not allowed
	 */
	public function setCurrentValue(mixed $value): self {
		if(!in_array($value, $this->values, true)){
			throw new \InvalidArgumentException("Invalid value '$value' for state '{$this->name}'. Allowed values: " . implode(", ", $this->values));
		}
		$this->currentValue = $value;
		return $this;
	}

	/**
	 * Gets the current value of the state property.
	 * @return mixed
	 */
	public function getCurrentValue(): mixed {
		return $this->currentValue;
	}

	/**
	 * Returns the NBT value definition for client (enum + name).
	 * @return array
	 */
	public function getValue(): array {
		return [
			"enum" => $this->values,
			"name" => $this->name
		];
	}
}