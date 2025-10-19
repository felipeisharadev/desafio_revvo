<?php
// Infrastructure/SimpleViewRenderer.php
namespace App\Infrastructure;

use App\Contracts\ViewRendererInterface;
use Exception;

class SimpleViewRenderer implements ViewRendererInterface
{
    protected string $basePath;

    public function __construct(string $basePath)
    {
        // Garante que o caminho base das views termine com /
        $this->basePath = rtrim($basePath, '/') . '/';
    }

    /**
     * @param string $viewName O nome da view, e.g., 'curso/index'.
     * @param array $viewData Dados a serem passados para a view.
     * @return string O HTML renderizado.
     */
    public function render(string $viewName, array $viewData = []): string
    {
        // 1. Sanitiza o nome da view para evitar ataque de Directory Traversal (../..)
        $sanitizedViewName = $this->sanitizeViewName($viewName);
        $viewFile = $this->basePath . $sanitizedViewName . '.php';
        
        // 2. Verifica se a view específica existe
        if (!file_exists($viewFile)) {
            throw new Exception("View file not found: " . $viewFile);
        }

        // 3. Define o arquivo de layout principal
        $layoutFile = $this->basePath . 'layout.php';
        if (!file_exists($layoutFile)) {
            throw new Exception("Layout file not found: " . $layoutFile);
        }

        // --- INÍCIO DA RENDERIZAÇÃO E BUFFERIZAÇÃO ---
        
        // As variáveis disponíveis no layout e na view serão: $viewFile e $viewData.
        // O $layoutFile será incluído no escopo atual.
        
        // 4. Inicia o buffer de saída
        ob_start();
        
        // 5. Inclui o arquivo de layout.
        // O layout precisa de $viewFile (o arquivo específico) e $viewData (os dados).
        // Estas variáveis são criadas no escopo local ANTES de incluir o layout.
        require $layoutFile; 
        
        // 6. Captura o conteúdo do buffer e limpa
        $output = ob_get_clean();
        
        return $output;
    }
    
    /**
     * Garante que o nome da view não tente acessar pastas fora do diretório base.
     */
    protected function sanitizeViewName(string $viewName): string
    {
        // Remove quaisquer sequências de '..' ou caminhos inválidos
        return str_replace(['..', '//'], ['', '/'], $viewName);
    }
}
