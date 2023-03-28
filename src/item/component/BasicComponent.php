<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

class BasicComponent implements ItemComponent {

	/**
	 * Basic Component allows you to create your own components
	 */
	public function __construct(private string $name, private mixed $value, private bool $property) { }

	public function getName(): string {
		return $this->name;
	}

	public function getValue(): mixed {
		return $this->value;
	}

	public function isProperty(): bool {
		return $this->property;
	}
}
