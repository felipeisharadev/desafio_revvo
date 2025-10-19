<?php
// contracts/RouterInterface.php
namespace App\Contracts;

interface RouterInterface
{
    /**
     * Adiciona uma nova rota (ex: $router->add('GET', '/', 'CursoController@index')).
     */
    public function add(string $method, string $uri, string $controllerAction): void;

    /**
     * Despacha a requisição, retornando o Controller e a Action.
     */
    public function dispatch(string $uri, string $method): array;
}