<?php
namespace App\Core;

final class Request
{
    public function __construct(
        public array $server,
        public array $query = [],
        public array $body = [],
        public array $files = [],
        public array $params = []
    ) {}

    public function __get(string $name): mixed
    {
        return match ($name) {
            'uri'    => parse_url($this->server['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/',
            'method' => strtoupper($this->server['REQUEST_METHOD'] ?? 'GET'),
            default  => null,
        };
    }

    public function get(?string $key = null, mixed $default = null): mixed
    {
        if ($key === null) return $this->query;
        return array_key_exists($key, $this->query) ? $this->query[$key] : $default;
    }

    public function post(?string $key = null, mixed $default = null): mixed
    {
        if ($key === null) return $this->body;
        return array_key_exists($key, $this->body) ? $this->body[$key] : $default;
    }

    public function input(string $key, mixed $default = null): mixed
    {
        if (array_key_exists($key, $this->body))  return $this->body[$key];
        if (array_key_exists($key, $this->query)) return $this->query[$key];
        return $default;
    }

    public function file(?string $key = null): mixed
    {
        if ($key === null) return $this->files;
        return $this->files[$key] ?? null;
    }

    public function withRouteParams(array $params): self
    {
        $this->params = $params;
        return $this;
    }
}
