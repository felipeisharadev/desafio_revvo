<?php
// Infrastructure/PDOConnection.php
namespace App\Infrastructure;

use App\Contracts\DBConnectionInterface;
use PDO;
use PDOException;
use Exception;

class PDOConnection implements DBConnectionInterface
{
    // Usaremos $connection para ser mais descritivo
    private ?PDO $connection = null; 

    /**
     * Construtor ajustado para SQLite.
     * @param array $config Deve conter 'database_path' (caminho para o arquivo .sqlite).
     * @throws Exception Se a conexão falhar.
     */
    public function __construct(array $config)
    {
        // Define :memory: como fallback
        $dbPath = $config['database_path'] ?? ':memory:'; 
        $dsn = "sqlite:{$dbPath}"; 

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, 
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            // Conexão
            $this->connection = new PDO($dsn, null, null, $options); 
            
            // ATIVAR FOREIGN KEYS (MUITO IMPORTANTE para SQLite)
            $this->connection->exec('PRAGMA foreign_keys = ON;'); 

        } catch (PDOException $e) {
            throw new Exception("Erro de conexão com o Banco de Dados: " . $e->getMessage());
        }
    }

    /**
     * Retorna a instância PDO.
     * @return PDO
     */
    private function getConnection(): PDO
    {
        if ($this->connection === null) {
            throw new Exception("Conexão com o banco de dados não inicializada.");
        }
        return $this->connection;
    }

    /**
     * Implementa query() para SELECT (retorna array).
     */
    public function query(string $sql, array $params = []): array
    {
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute($params);
        
        // FORÇAR O FETCH_ASSOC aqui para garantir que o array seja associativo.
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Implementa exec() para INSERT/UPDATE/DELETE (retorna int linhas afetadas).
     */
    public function exec(string $sql, array $params = []): int
    {
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    }
    
    /**
     * Implementa lastInsertId().
     */
    public function lastInsertId(): int|string
    {
        return $this->getConnection()->lastInsertId();
    }
}
