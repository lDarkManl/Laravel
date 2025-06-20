<?php
namespace src;

class Database {
    private \PDO $pdo;

    public function __construct($dbHost, $dbName, $dbUser, $dbPass) {
        try {
            $this->pdo = new \PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8", $dbUser, $dbPass);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            throw new \PDOException("Ошибка подключения к БД: " . $e->getMessage(), 0, $e);
        }
    }

    public function createTables(): void {
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS statuses (
            id INT PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(50) NOT NULL
        )");
        $this->pdo->exec("INSERT IGNORE INTO statuses (id, name) VALUES
            (1, 'Новая'),
            (2, 'Отклонить'),
            (3, 'Принять')");

        $this->pdo->exec("CREATE TABLE IF NOT EXISTS users (
            id INT PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(100),
            email VARCHAR(100),
            status_id INT DEFAULT 1,
            FOREIGN KEY (status_id) REFERENCES statuses(id)
        )");

        $this->pdo->exec("CREATE TABLE IF NOT EXISTS requests (
            id INT PRIMARY KEY AUTO_INCREMENT,
            email VARCHAR(100),
            age INT,
            status_id INT DEFAULT 1,
            FOREIGN KEY (status_id) REFERENCES statuses(id)
        )");
    }

    public function getTables(): array {
        $stmt = $this->pdo->query("SHOW TABLES");
        $result = $stmt->fetchAll(\PDO::FETCH_NUM);
        $tables = [];
        foreach ($result as $row) {
            if ($row[0] !== 'statuses') {
                $tables[] = $row[0];
            }
        }
        return $tables;
    }

    public function getTableColumns(string $table): array
    {
        $stmt = $this->pdo->query("SHOW COLUMNS FROM `$table`");
        $columns = [];

        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $columns[] = $row['Field'];
        }

        return $columns;
    }

    public function getAll($table, $sortField = 'id', $sortOrder = 'ASC'): array {
        $sql = "SELECT `$table`.*, s.name AS status_name FROM `$table` LEFT JOIN statuses s ON `$table`.status_id = s.id ORDER BY `$sortField` $sortOrder";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function add($table, array $data): bool {
        $keys = array_keys($data);
        $columns = '`' . implode('`, `', $keys) . '`';
        $placeholders = ':' . implode(', :', $keys);
        $stmt = $this->pdo->prepare("INSERT INTO `$table` ($columns) VALUES ($placeholders)");
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        return $stmt->execute();
    }

    public function update($table, $id, array $data): bool {
        $fields = "";
        foreach ($data as $key => $value) {
            $fields .= "`$key` = :$key, ";
        }
        $fields = rtrim($fields, ', ');
        $stmt = $this->pdo->prepare("UPDATE `$table` SET $fields WHERE id = :id");
        $data['id'] = $id;
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        return $stmt->execute();
    }

    public function delete($table, $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM `$table` WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function tablesExist(): bool {
        $stmt = $this->pdo->query("SHOW TABLES LIKE 'requests'");
        return (bool)$stmt->fetch();
    }
}