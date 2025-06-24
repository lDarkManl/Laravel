<?php
namespace src;

use PDO;
use PDOException;

class Database
{
    protected PDO $pdo;
    protected static $instance = null;

    public function __construct(string $dbHost, string $dbName, string $dbUser, string $dbPass)
    {
        try {
            $this->pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8", $dbUser, $dbPass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new PDOException("Ошибка подключения к БД: " . $e->getMessage(), 0, $e);
        }
    }

    public static function getInstance(string $dbHost, string $dbName, string $dbUser, string $dbPass): self
    {
        if (self::$instance === null) {
            self::$instance = new self($dbHost, $dbName, $dbUser, $dbPass);
        }
        return self::$instance;
    }

    public function query(string $sql, array $params = []): array
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function execute(string $sql, array $params = []): bool
    {
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    public function getPdo(): PDO
    {
        return $this->pdo;
    }

    public function lastInsertId(): string
    {
        return $this->pdo->lastInsertId();
    }
}