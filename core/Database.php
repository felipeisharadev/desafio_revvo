<?php
declare(strict_types=1);

final class Database
{
    private static ?Database $instance = null;
    private \PDO $pdo;

    private function __construct()
    {
        $dbPath = __DIR__ . '/../database/database.sqlite'; // <<--- mudou
        if (!is_file($dbPath)) {
            http_response_code(500);
            exit('database/database.sqlite não encontrado. Rode "php database/migrate.php && php database/seed.php".');
        }
        $this->pdo = new \PDO('sqlite:' . $dbPath, null, null, [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
        ]);
        // Opcional: reduzir locking em dev
        // $this->pdo->exec('PRAGMA journal_mode = WAL;');
    }

    public static function get(): Database
    {
        if (!self::$instance) self::$instance = new self();
        return self::$instance;
    }

    public function pdo(): \PDO
    {
        return $this->pdo;
    }
}
