<?php
declare(strict_types=1);

final class Bootstrap
{
    public static function dispatch(string $router, string $action): void
    {
        if ($router === 'home') {
            View::render('home/index');
            return;
        }

        if ($router === 'cursos') {
            require_once __DIR__ . '/../controllers/CursoController.php';
            $c = new CursoController();
            switch ($action) {
                case 'list':   $c->list();   break;
                case 'create': $c->create(); break;
                case 'edit':   $c->edit();   break;
                case 'delete': $c->delete(); break;
                default:       $c->list();   break;
            }
            return;
        }

        if ($router === 'cursos') {
            require_once __DIR__ . '/../controllers/CursoController.php';
            $c = new CursoController();
            switch ($action) {
                case 'list':   $c->list();   break;
                case 'create': $c->create(); break;
                case 'edit':   $c->edit();   break;
                case 'delete': $c->delete(); break;
                default:       $c->list();   break;
            }
            return;
        }

        http_response_code(404);
        echo 'Rota não encontrada.';
    }
}
