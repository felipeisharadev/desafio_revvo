<?php
// app/Core/Database.php
namespace App\Core;

use PDO;
use PDOException;
use Exception;

final class Database
{
    /** @var PDO|null */
    private static ?PDO $connection = null;

    /**
     * Inicializa a conexão do request.
     * Chamado uma única vez no Bootstrap.
     */
    public static function init(array $config): void
    {
        if (self::$connection !== null) {
            return;
        }

        $dsn  = $config['dsn'] ?? null;
        $user = $config['user'] ?? null;
        $pass = $config['pass'] ?? null;

        if (!$dsn) {
            if (isset($config['database_path'])) {
                $dsn = 'sqlite:' . $config['database_path'];
            } else {
                throw new Exception("Configuração de banco inválida: DSN ausente.");
            }
        }

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $pdo = new PDO($dsn, $user, $pass, $options);
            $pdo->exec('PRAGMA foreign_keys = ON;'); 
            self::$connection = $pdo;
        } catch (PDOException $e) {
            throw new Exception("Erro ao conectar ao banco: " . $e->getMessage());
        }
    }

    /**
     * Retorna a conexão ativa.
     */
    public static function conn(): PDO
    {
        if (!self::$connection) {
            throw new Exception("Banco de dados não inicializado. Chame Database::init() no Bootstrap.");
        }
        return self::$connection;
    }

    /**
     * Métodos utilitários para transações
     */
    public static function beginTransaction(): void
    {
        self::conn()->beginTransaction();
    }

    public static function commit(): void
    {
        self::conn()->commit();
    }

    public static function rollBack(): void
    {
        if (self::conn()->inTransaction()) {
            self::conn()->rollBack();
        }
    }

    public static function inTransaction(): bool
    {
        return self::conn()->inTransaction();
    }
}
