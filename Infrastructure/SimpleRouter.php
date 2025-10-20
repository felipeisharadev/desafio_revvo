<?php
namespace App\Infrastructure;

use App\Interfaces\RouterInterface;

final class SimpleRouter implements RouterInterface
{
    private array $routes = [];

    public function add(string $method, string $path, string $handler): void
    {
        $this->routes[strtoupper($method)][$path] = $handler;
    }

    public function dispatch(string $path, string $method): array
    {
        $path   = strtok($path, '?') ?: '/';
        $method = strtoupper($method);

        if (!isset($this->routes[$method][$path])) {
            return ['controller'=>'App\\Controllers\\NotFoundController','action'=>'index','vars'=>[]];
        }

        [$ctrl, $act] = explode('@', $this->routes[$method][$path]);
        return ['controller' => 'App\\Controllers\\' . $ctrl, 'action' => $act, 'vars'=>[]];
    }
}
