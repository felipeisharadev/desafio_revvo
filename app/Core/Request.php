<?php
namespace App\Core;

final class Request
{
    public function __construct(
        public array  $server,
        public array  $query,
        public array  $body
    ) {}

    public function __get(string $name): mixed
    {
        return match ($name) {
            'uri'    => parse_url($this->server['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/',
            'method' => strtoupper($this->server['REQUEST_METHOD'] ?? 'GET'),
            default  => null,
        };
    }
}
