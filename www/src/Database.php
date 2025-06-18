<?php

namespace src;
class Database {
    private $pdo;

    public function __construct($dbHost, $dbName, $dbUser, $dbPass)
    {
        try {
            $this->pdo = new \PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8", $dbUser, $dbPass);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        } catch (\PDOException $e) {
            throw \PDOException("Ошибка подключения к БД: " . $e->getMessage(), 0, $e);
        }
    }

    public function createTable() {

        // Таблица статусов
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS statuses (
            id INT PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(50) NOT NULL
        )");

        $this->pdo->exec("INSERT IGNORE INTO statuses (id, name) VALUES
            (1, 'Новая'),
            (2, 'Отклонить'),
            (3, 'Принять')");

        // Таблица заявок
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS requests (
            id INT PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(100),
            email VARCHAR(100),
            status_id INT DEFAULT 1,
            FOREIGN KEY (status_id) REFERENCES statuses(id)
        )");
    }

    public function getAll($sortField = 'id', $sortOrder = 'ASC') {
        if (!$this->tablesExist()) {
            return false;
        }

        try {
            $allowedFields = ['id', 'name', 'email', 'status_id', 'created_at'];
            $sortField = in_array($sortField, $allowedFields) ? $sortField : 'id';
            $sortOrder = strtoupper($sortOrder) === 'DESC' ? 'DESC' : 'ASC';

            $stmt = $this->pdo->prepare(
                "SELECT r.id, r.name, r.email, r.status_id, s.name as status_name 
             FROM requests r LEFT JOIN statuses s ON r.status_id = s.id 
             ORDER BY `$sortField` $sortOrder"
            );
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Ошибка выборки данных: " . $e->getMessage());
            return [];
        }
    }

    public function tablesExist(): bool {
        try {
            $stmt = $this->pdo->query("SHOW TABLES LIKE 'requests'");
            return (bool)$stmt->fetch();
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function add($name, $email) {
        $stmt = $this->pdo->prepare("INSERT INTO requests (name, email) VALUES (?, ?)");
        return $stmt->execute([$name, $email]);
    }

    public function updateStatus($id, $statusId) {
        $stmt = $this->pdo->prepare("UPDATE requests SET status_id = ? WHERE id = ?");
        return $stmt->execute([$statusId, $id]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM requests WHERE id = ?");
        return $stmt->execute([$id]);
    }

}