<?php
namespace App\Infrastructure;

use App\Interfaces\RouterInterface;

final class SimpleRouter implements RouterInterface
{
    private array $routes = [];
    private array $staticMap = [];

    public function add(string $method, string $path, string $handler): void
    {
        $method = strtoupper($method);
        $path   = rtrim($path, '/') ?: '/';

        if (preg_match_all('#\{([^/]+)\}#', $path, $m)) {
            $paramNames = $m[1]; // ex.: ['id']
            $regex = preg_replace('#\{[^/]+\}#', '([^/]+)', $path);
            $regex = '#^' . $regex . '$#';

            $this->routes[$method][] = [
                'raw'     => $path,
                'handler' => $handler,
                'regex'   => $regex,
                'params'  => $paramNames,
            ];
        } else {
            $this->staticMap[$method][$path] = $handler;
        }
    }

    public function dispatch(string $path, string $method): array
    {
        $method = strtoupper($method);
        $path   = strtok($path, '?') ?: '/';
        $path   = rtrim($path, '/') ?: '/';

        if (isset($this->staticMap[$method][$path])) {
            [$ctrl, $act] = explode('@', $this->staticMap[$method][$path], 2);
            return [
                'controller' => 'App\\Controllers\\' . $ctrl,
                'action'     => $act,
                'vars'       => [],
            ];
        }

        foreach ($this->routes[$method] ?? [] as $route) {
            if (preg_match($route['regex'], $path, $matches)) {
                array_shift($matches); // remove match completo
                $vars = [];
                foreach ($route['params'] as $i => $name) {
                    $vars[$name] = $matches[$i] ?? null;
                }
                [$ctrl, $act] = explode('@', $route['handler'], 2);
                return [
                    'controller' => 'App\\Controllers\\' . $ctrl,
                    'action'     => $act,
                    'vars'       => $vars,
                ];
            }
        }

        return [
            'controller' => 'App\\Controllers\\NotFoundController',
            'action'     => 'index',
            'vars'       => [],
        ];
    }
}
