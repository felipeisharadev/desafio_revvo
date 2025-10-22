<?php
namespace App\Interfaces;

interface RouterInterface
{
    public function add(string $method, string $uri, string $controllerAction): void;

    public function dispatch(string $uri, string $method): array;
}