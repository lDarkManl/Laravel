<?php

namespace classes\interfaces;

interface RequestInterface
{
    public function method(): string;

    public function url(): string;

    public function get(string $key = null, $default = ''): string;
    public function post(string $key = null, $default = ''): string;

    public function all(): array;

}