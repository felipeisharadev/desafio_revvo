<?php
namespace App\Core;

use App\Infrastructure\SimpleRouter;
use App\Infrastructure\SimpleViewRenderer;

final class Bootstrap
{
    public function start(): Application
    {
        $dbConfig = require ROOT_PATH . '/config/database.php';
        Database::init($dbConfig);

        $router = $this->buildRouter();
        $view   = $this->buildViewRenderer();

        return new Application($router, $view);
    }

    private function buildRouter(): SimpleRouter
    {
        $router = new SimpleRouter();
        require ROOT_PATH . '/config/routes.php'; // popula $router
        return $router;
    }

    private function buildViewRenderer(): SimpleViewRenderer
    {
        return new SimpleViewRenderer(ROOT_PATH . '/app/Views');
    }
}
