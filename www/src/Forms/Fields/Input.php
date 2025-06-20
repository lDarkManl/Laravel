<?php

namespace src\Forms\Fields;

class Input extends BaseField
{
    const TPL_NAME = "input";

    protected string $type = "text";

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

}
