<?php

namespace classes\interfaces;

interface RequestInterface
{
    public function method(): string;

    public function url(): string;

    public function query(string $key = null, $default = null): string;
    public function post(string $key = null, $default = null): string;

    public function all(): array;

}