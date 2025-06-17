<?php

namespace src;

class Request
{
    public function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    public function post(): array
    {
        return $_POST;
    }
}