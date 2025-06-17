<?php

namespace src\Forms\Fields;

class Input extends BaseField
{
    const TMP_NAME = "input";
    
    public function render(): string
    {
        ob_start();
        require 'templates/'.self::TMP_NAME.'.php';
        return ob_get_clean();
    }
}