<?php

namespace App\Router;

class Route
{
    public string $path;
    public string $controller;
    public string $action;
    public array $methods;

    public function __construct(string $path, string $controller, string $action, array $methods)
    {
        $this->path = $path;
        $this->controller = $controller;
        $this->action = $action;
        $this->methods = array();

        foreach ($methods as $method) {
            $this->methods[] = strtoupper($method);
        }
    }
}