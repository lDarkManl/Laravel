<?php
namespace src\Forms;


use src\Forms\Fields\BaseField;

class Form {
    const TPL_NAME = "form";

    protected array $fields = [];

    public function addField(BaseField $field): void
    {
        $this->fields[] = $field;
    }

    public function populate(array $data): void {
        foreach ($this->fields as $field) {
            $name = $field->getName();
            $field->setValue($data[$name] ?? null);
        }
    }

    public function getFields(): array
    {
        return $this->fields;
    }

    public function render(): string
    {
        ob_start();
        extract(['form' => $this]);
        require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . self::TPL_NAME . '.tpl.php';
        return ob_get_clean();
    }
}