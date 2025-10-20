<?php

namespace App\Infrastructure;

use App\Interfaces\ViewRendererInterface;
use Exception;

class SimpleViewRenderer implements ViewRendererInterface
{
    protected string $basePath;

    public function __construct(string $basePath)
    {
        $this->basePath = rtrim($basePath, '/') . '/';
    }

    /**
     * @param string 
     * @param array 
     * @return string 
     */
    public function render(string $viewName, array $viewData = []): string
    {
        $sanitizedViewName = $this->sanitizeViewName($viewName);
        $viewFile = $this->basePath . $sanitizedViewName . '.php';
        
        if (!file_exists($viewFile)) {
            throw new Exception("View file not found: " . $viewFile);
        }

        $layoutFile = $this->basePath . 'layout.php';
        if (!file_exists($layoutFile)) {
            throw new Exception("Layout file not found: " . $layoutFile);
        }

        ob_start();
        require $layoutFile; 
    
        $output = ob_get_clean();
        return $output;
    }
    
    protected function sanitizeViewName(string $viewName): string
    {
        return str_replace(['..', '//'], ['', '/'], $viewName);
    }
}
