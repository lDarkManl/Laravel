<?php

namespace src;

class Request
{
    protected array $postData;
    protected array $serverData;

    protected array $getData;

    public function __construct()
    {
        $this->postData = $_POST;
        $this->serverData = $_SERVER;
        $this->getData = $_GET;
    }
    public function isPost(): bool
    {
        return $this->serverData['REQUEST_METHOD'] === 'POST';
    }

    public function get($key): mixed
    {
        return $this->getData[$key] ?? null;
    }

    public function post($key): mixed
    {
        return $this->postData[$key] ?? null;
    }

    public function isSetPost($key): bool
    {
        return isset($this->postData[$key]);
    }

}
