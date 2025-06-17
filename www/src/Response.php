<?php

namespace src;

class Response
{
    private array $renderObjects = [];

    public function setRenderObject(mixed $obj): void
    {
        $this->renderObjects[] = $obj;
    }

    public function render(): string
    {
        ob_start();
        foreach($this->renderObjects as $obj)
        {
            echo $obj->render();
        }
        return ob_get_clean();
    }
}