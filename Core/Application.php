<?php

namespace App\Core; 

use App\Contracts\RouterInterface;
use App\Contracts\DBConnectionInterface;
use App\Contracts\ViewRendererInterface; 


class Application
{
    private RouterInterface $router;
    private DBConnectionInterface $db;
    private ViewRendererInterface $renderer;
    
    public function __construct(
        RouterInterface $router, 
        DBConnectionInterface $db,
        ViewRendererInterface $renderer 
    ) {
        $this->router = $router;
        $this->db = $db;
        $this->renderer = $renderer;
    }

    public function handle(Request $request): string
    {
        $routeInfo = $this->router->dispatch($request->uri, $request->method);
        $controllerName = $routeInfo['controller'];
        $action = $routeInfo['action'];
        $controller = new $controllerName($this->db, $this->renderer); 
        $content = $controller->$action($request); // <--- DEVE PASSAR O $request!

        return $content; 
    }
}