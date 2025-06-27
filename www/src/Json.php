<?php

namespace src;

class Json {

    protected array $params = [];

    public function setParams(array $params): void
    {
        $this->params = $params;
    }

    public function getJson(): string
    {
        return json_encode($this->params);
    }
}
