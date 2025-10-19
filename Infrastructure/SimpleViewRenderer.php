<?php
// infrastructure/SimpleViewRenderer.php
namespace App\Infrastructure;

use App\Contracts\ViewRendererInterface;

class SimpleViewRenderer implements ViewRendererInterface
{
    private string $viewPath;

    // O caminho base das views (../views/) é injetado, não hardcoded.
    public function __construct(string $basePath)
    {
        $this->viewPath = $basePath;
    }

    public function render(string $view, array $data = []): string
    {
        // 1. Inicia o buffer de saída (captura o conteúdo do require)
        ob_start();
        
        extract($data, EXTR_SKIP);
        $cleanViewName = str_replace(['..', './', '\\'], '', $viewName);
        $viewFile = $this->viewPath . '/' . $view . '.php';

        if (!is_file($viewFile)) {
            throw new \RuntimeException("View não encontrada: {$viewFile}");
        }
        
        // Carrega o layout, que deve incluir o conteúdo da view ($viewFile)
        require $this->viewPath . '/layout.php'; 
        
        // 2. Retorna o conteúdo do buffer (em vez de 'void'/'exit')
        return ob_get_clean();
    }

    public static function e(string $v): string
    {
        return htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
    }
}