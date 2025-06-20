<?php
namespace src\Forms\Fields;

class Select extends BaseField
{
    const TPL_NAME = "select";
    protected array $options = [];

    public function __construct(string $name, string $label, array $options)
    {
        parent::__construct($name, $label);
        $this->options = $options;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

}