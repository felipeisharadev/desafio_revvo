<?php
// infrastructure/SimpleRouter.php
namespace App\Infrastructure;

use App\Contracts\RouterInterface;

class SimpleRouter implements RouterInterface
{
    private array $routes = [];

    public function add(string $method, string $uri, string $controllerAction): void
    {
        $this->routes[$method][$uri] = $controllerAction;
    }

    public function dispatch(string $uri, string $method): array
    {
        $uri = strtok($uri, '?');
        
        if (isset($this->routes[$method][$uri])) {
            list($controllerName, $action) = explode('@', $this->routes[$method][$uri]);
            
            return [
                'controller' => 'App\\Controllers\\' . $controllerName,
                'action' => $action
            ];
        }
        
        return ['controller' => 'App\\Controllers\\NotFoundController', 'action' => 'index'];
    }
}