<?php

namespace src\Forms\Fields;

abstract class BaseField
{

    const TPL_NAME = null;
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

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setValue(mixed $value): void
    {
        $this->value = $value;
    }

    public function render(): string
    {
        ob_start();
        extract(['field' => $this]);
        require $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'templates/'.static::TPL_NAME.'.tpl.php';
        return ob_get_clean();
    }
}

