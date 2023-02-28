<?php

namespace customiesdevs\customies\item\component;

class BasicComponent implements ItemComponent
{

    /**
     * Basic Component allows you to create your own components
     *
     * @param string $name
     * @param mixed $value
     * @param bool $property
     */
    public function __construct(private string $name, private mixed $value, private bool $property) {}

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getValue(): mixed
    {
        return $this->value;
    }

    /**
     * @return bool
     */
    public function isProperty(): bool
    {
        return $this->property;
    }
}