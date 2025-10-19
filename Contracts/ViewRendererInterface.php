<?php
// contracts/ViewRendererInterface.php
namespace App\Contracts;

interface ViewRendererInterface
{
    public function render(string $view, array $data = []): string;
}