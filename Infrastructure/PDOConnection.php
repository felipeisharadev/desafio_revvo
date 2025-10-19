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
        $dbPath = $config['database_path'] ?? ':memory:'; 
        $dsn = "sqlite:{$dbPath}"; 

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, 
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->connection = new PDO($dsn, null, null, $options); 
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

    public function query(string $sql, array $params = []): array
    {
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function exec(string $sql, array $params = []): int
    {
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    }
    
    public function lastInsertId(): int|string
    {
        return $this->getConnection()->lastInsertId();
    }
}
