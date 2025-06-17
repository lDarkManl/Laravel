<?php

namespace src\Forms;

use src\Forms\Fields\BaseField;

abstract class BaseForm
{
    const TMP_NAME = "form";

    protected array $fields = [];

    public abstract function send(string $address): bool;

    public function populate(array $data): void
    {
        foreach ($this->fields as $field) {
            $name = $field->getName();
            $field->setValue($data[$name] ?? null);

        }
    }

    public function render(): string
    {
        extract([
            'form' => $this,
        ]);
        ob_start();
        require_once 'templates/'.static::TMP_NAME.'.php';
        return ob_get_clean();
    }
}