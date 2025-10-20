<?php
// Interfaces/ViewRendererInterface.php
namespace App\Interfaces;

interface ViewRendererInterface
{
    public function render(string $view, array $data = []): string;
}