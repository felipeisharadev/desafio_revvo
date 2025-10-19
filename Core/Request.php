<?php
namespace App\Core;

class Request
{
    public readonly string $uri;
    public readonly string $method;
    public readonly array $query; 
    public readonly array $body; 

    public function __construct(array $server, array $get = [], array $post = [])
    {
        $this->uri = $server['REQUEST_URI'] ?? '/';
        $this->method = $server['REQUEST_METHOD'] ?? 'GET';
        $this->query = $get;
        $this->body = $post;
    }
}