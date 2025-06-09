<?php

namespace classes\interfaces;

class FormField
{

    /** @var ValidatorInterface[]  */
    protected array $validators = [];
    protected string $name;
    protected string $type;
    protected array $attrs;
    protected mixed $value;

    public function isValid(): bool {
        return false;
    }
}