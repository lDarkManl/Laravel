<?php

namespace src\Forms\Fields;

abstract class BaseField
{
    protected string $name;
    protected string $value = '';
    protected string $label;

    public function __construct(string $name, string $label)
    {
        $this->name = $name;
        $this->label = $label;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(mixed $value): void
    {
        $this->value = $value;
    }

    abstract public function render(): string;
}