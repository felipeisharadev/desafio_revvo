<?php

namespace App\Core; 

use App\Interfaces\RouterInterface;
use App\Interfaces\DBConnectionInterface;
use App\Interfaces\ViewRendererInterface; 


class Application
{
    private RouterInterface $router;
    private ViewRendererInterface $renderer;
    
    public function __construct(
        RouterInterface $router, 
        ViewRendererInterface $renderer 
    ) {
        $this->router = $router;
        $this->renderer = $renderer;
    }
    
    public function handle(Request $request): string
    {
        $routeInfo = $this->router->dispatch($request->uri, $request->method);

        $controllerName = $routeInfo['controller'];
        $action         = $routeInfo['action'];

        if (!empty($routeInfo['vars'])) {
            $request->withRouteParams($routeInfo['vars']);
        }

        $controller = new $controllerName($this->renderer);
        $content    = $controller->$action($request);

        return $content;
    }

}