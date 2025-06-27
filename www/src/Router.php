<?php

namespace src;

class Router
{

    protected $routes = [];
    protected $uri;
    protected $method;

    protected $notFound = false;

    public function __construct()
    {
        $request = new Request();
        $this->uri = $request->uri();
        $this->method = $request->method();
    }

    public function match(): array
    {
        foreach ($this->routes as $route) {
            if ($route['method'] !== strtoupper($this->method))
            {
                continue;
            }
            if (preg_match($route['uri'], $this->uri)) {
                return $route['controller'];
            }

        }

        $this->notFound = true;

        if ($this->isApi())
        {
            return ['class' => \src\Controllers\ApiNotFoundController::class, 'method' => 'get'];
        }

        return ['class' => \src\Controllers\NotFoundController::class, 'method' => 'get'];
    }

    public function add($uri, $controller, $method): void
    {
        $this->routes[] = [
            'uri' => '#' . $uri . '#',
            'controller' => $controller,
            'method' => $method,
        ];
    }

    public function get($uri, $controller): void
    {
        $this->add($uri, $controller, 'GET');
    }

    public function post($uri, $controller): void
    {
        $this->add($uri, $controller, 'POST');
    }

    public function notFound(): bool
    {
        return $this->notFound;
    }
    public function isApi(): bool
    {
        return preg_match("#^(/api/v1|/index.php/api/v1)#", $this->uri);
    }


}