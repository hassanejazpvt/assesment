<?php

declare(strict_types=1);

namespace Hassan\Assesment\Core;

class Route
{
    protected $type;
    protected $ogRoute;
    protected $route;
    protected $controller;
    protected $method;
    protected $args = [];

    public function __construct(string $type, string $route, string $controller, string $method)
    {
        $this->type = $type;
        preg_match_all('/\{[^\}\?]+\}/', $route, $args);
        $this->args       = $args[0] ?? [];
        $this->ogRoute    = $route;
        $this->route      = ('/' == $route ? $route : trim(str_replace($this->args, '', $route), '/'));
        $this->controller = $controller;
        $this->method     = $method;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getRoute(): string
    {
        return $this->route;
    }

    public function getOgRoute(): string
    {
        return $this->ogRoute;
    }

    public function getController(): string
    {
        return $this->controller;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getArgs(): array
    {
        return $this->args;
    }
}
