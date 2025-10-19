<?php
// Core/Bootstrap.php
namespace App\Core;

use App\Contracts\DBConnectionInterface;
use App\Contracts\RouterInterface;
use App\Contracts\ViewRendererInterface;
use App\Infrastructure\PDOConnection; 
use App\Infrastructure\SimpleRouter; 
use App\Infrastructure\SimpleViewRenderer;
use Exception;

class Bootstrap
{
    public function start(): Application
    {
        $router = $this->createRouter();
        $dbConnection = $this->createDatabaseConnection();
        $viewRenderer = $this->createViewRenderer(); 
        
        $app = new Application($router, $dbConnection, $viewRenderer); 

        return $app;
    }

    protected function createDatabaseConnection(): DBConnectionInterface
    {
        $dbConfigFile = ROOT_PATH . '/config/database.php';
        
        if (!file_exists($dbConfigFile)) {
            throw new Exception("Arquivo de configuração do banco de dados não encontrado em: " . $dbConfigFile);
        }
        
        $config = require $dbConfigFile; 

        if (!isset($config['database_path'])) {
             throw new Exception("Chave 'database_path' ausente no arquivo de configuração do banco de dados.");
        }

        try {
            return new PDOConnection($config);
        } catch (Exception $e) {
            throw $e;
        }
    }

    protected function createRouter(): RouterInterface
    {
        $router = new SimpleRouter();
        require ROOT_PATH . '/config/routes.php';
        return $router;
    }
    
    protected function createViewRenderer(): ViewRendererInterface
    {
        $basePath = ROOT_PATH . '/views';
        return new SimpleViewRenderer($basePath);
    }
}
