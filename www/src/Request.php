<?php

namespace src;

class Request
{
    protected array $postData;
    protected array $serverData;
    protected array $getData;

    protected array $sessionData;

    public function __construct()
    {
        $this->postData = $_POST;
        $this->serverData = $_SERVER;
        $this->getData = $_GET;
        $this->sessionData = $_COOKIE;
    }
    public function isPost(): bool
    {
        return $this->serverData['REQUEST_METHOD'] === 'POST';
    }

    public function get($key): mixed
    {
        return $this->getData[$key] ?? null;
    }

    public function post($key = null): mixed
    {
        if ($key)
            return $this->postData[$key] ?? null;
        return $this->postData;
    }

    public function uri(): string
    {
        return $this->serverData['REQUEST_URI'];
    }

    public function method(): string
    {
        return $this->serverData['REQUEST_METHOD'];
    }

    public function setError(string $error): void
    {
        $this->sessionData['error'] = $error;
    }

    public function getError(): string
    {
        return $this->sessionData['error'] ?? '';
    }
}
