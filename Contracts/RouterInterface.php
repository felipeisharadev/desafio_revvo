<?php
// contracts/RouterInterface.php
namespace App\Contracts;

interface RouterInterface
{
    public function add(string $method, string $uri, string $controllerAction): void;

    public function dispatch(string $uri, string $method): array;
}