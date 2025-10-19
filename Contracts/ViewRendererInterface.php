<?php
// contracts/ViewRendererInterface.php
namespace App\Contracts;

interface ViewRendererInterface
{
    /**
     * Renderiza o template de visualização.
     */
    public function render(string $view, array $data = []): string;

    /**
     * Escapa caracteres especiais para HTML.
     */
    public static function e(string $v): string;
}