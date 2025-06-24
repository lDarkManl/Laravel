<?php
namespace src\Migrations;

use src\Database;

class MigrationManager
{
    protected Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance(DB_HOST, DB_NAME, DB_USER, DB_PASS);
    }

    public function run(): void
    {
        $this->createStatusesTable();
        $this->createUsersTable();
        $this->createRequestsTable();
    }

    protected function createStatusesTable(): void
    {
        $this->db->execute("CREATE TABLE IF NOT EXISTS statuses (
            id INT PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(50) NOT NULL
        )");
        $this->db->execute("INSERT IGNORE INTO statuses (id, name) VALUES
            (1, 'Новая'),
            (2, 'Отклонить'),
            (3, 'Принять')");
    }

    protected function createUsersTable(): void
    {
        $this->db->execute("CREATE TABLE IF NOT EXISTS users (
            id INT PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(100),
            email VARCHAR(100),
            status_id INT DEFAULT 1,
            FOREIGN KEY (status_id) REFERENCES statuses(id)
        )");
    }

    protected function createRequestsTable(): void
    {
        $this->db->execute("CREATE TABLE IF NOT EXISTS requests (
            id INT PRIMARY KEY AUTO_INCREMENT,
            email VARCHAR(100),
            age INT,
            status_id INT DEFAULT 1,
            FOREIGN KEY (status_id) REFERENCES statuses(id)
        )");
    }
}