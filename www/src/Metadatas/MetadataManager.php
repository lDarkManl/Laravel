<?php
namespace src\Metadatas;

use src\Database;

class MetadataManager
{
    protected Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function tablesExist(): bool
    {
        $sql = "SHOW TABLES LIKE 'requests'";
        return !empty($this->db->query($sql));
    }

    public function getTables(): array
    {
        $sql = "SHOW TABLES";
        $result = $this->db->query($sql);
        $tables = [];
        foreach ($result as $row) {
            $tableName = array_values($row)[0];
            if ($tableName !== 'statuses') {
                $tables[] = $tableName;
            }
        }
        return $tables;
    }
}