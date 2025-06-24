<?php
namespace src\QueryBuilders;

use src\Database;

abstract class QueryBuilder
{
    const ?string TABLE_NAME = null;

    protected array $selectFields = ['*'];
    protected array $whereFields = [];
    protected array $bindings = [];
    protected int $limit = 0;
    protected int $offset = 0;
    protected array $orderFields = [];
    protected array $joins = [];
    protected Database $sqlConnection;

    public function __construct()
    {
        $this->sqlConnection = Database::getInstance(DB_HOST, DB_NAME, DB_USER, DB_PASS);
    }

    public function select(array $fields = ['*']): static
    {
        $this->selectFields = $fields;
        return $this;
    }

    public function where(string $column, string $operator, $value): static
    {
        $paramName = ':where_' . $column . '_' . count($this->whereFields);
        $this->whereFields[] = "`$column` $operator $paramName";
        $this->bindings[$paramName] = $value;
        return $this;
    }

    public function orderBy(string $fieldName, string $order = 'ASC'): static
    {
        $this->orderFields[$fieldName] = strtoupper($order);
        return $this;
    }

    public function limit(int $limit): static
    {
        $this->limit = $limit;
        return $this;
    }

    public function offset(int $offset): static
    {
        $this->offset = $offset;
        return $this;
    }

    public function leftJoin(string $table, string $first, string $operator, string $second, string $alias = null): static
    {
        $joinClause = "LEFT JOIN `$table`";
        if ($alias) {
            $joinClause .= " AS `$alias`";
        }
        $first = trim($first, '`');
        $second = trim($second, '`');
        $joinClause .= " ON `$first` $operator `$second`";
        $this->joins[] = $joinClause;
        return $this;
    }

    public function insert(array $attributes): string
    {
        $keys = array_keys($attributes);
        $columns = '`' . implode('`, `', $keys) . '`';
        $placeholders = ':' . implode(', :', $keys);
        $sql = "INSERT INTO `" . static::TABLE_NAME . "` ($columns) VALUES ($placeholders)";
        $params = array_combine(array_map(fn($key) => ":$key", $keys), $attributes);
        $this->sqlConnection->execute($sql, $params);
        return $this->sqlConnection->lastInsertId();
    }

    public function update(array $attributes): bool
    {
        $fields = '';
        $params = [];
        foreach ($attributes as $key => $value) {
            $fields .= "`$key` = :$key, ";
            $params[":$key"] = $value;
        }
        $fields = rtrim($fields, ', ');
        $sql = "UPDATE `" . static::TABLE_NAME . "` SET $fields";
        if ($this->whereFields) {
            $sql .= ' WHERE ' . implode(' AND ', $this->whereFields);
            $params = array_merge($params, $this->bindings);
        }
        return $this->sqlConnection->execute($sql, $params);
    }

    public function delete(): bool
    {
        $sql = "DELETE FROM `" . static::TABLE_NAME . "`";
        if ($this->whereFields) {
            $sql .= ' WHERE ' . implode(' AND ', $this->whereFields);
        }
        return $this->sqlConnection->execute($sql, $this->bindings);
    }

    public function makeSql(): string
    {
        $sql = 'SELECT ' . implode(', ', $this->selectFields);
        $sql .= ' FROM `' . static::TABLE_NAME . '`';

        if ($this->joins) {
            $sql .= ' ' . implode(' ', $this->joins);
        }

        if ($this->whereFields) {
            $sql .= ' WHERE ' . implode(' AND ', $this->whereFields);
        }

        if ($this->orderFields) {
            $orderClauses = [];
            foreach ($this->orderFields as $field => $order) {
                $orderClauses[] = "`$field` $order";
            }
            $sql .= ' ORDER BY ' . implode(', ', $orderClauses);
        }

        if ($this->limit) {
            $sql .= " LIMIT $this->limit";
        }

        if ($this->offset) {
            $sql .= " OFFSET $this->offset";
        }

        return $sql . ';';
    }

    public function get(): array
    {
        $sql = $this->makeSql();
        return $this->sqlConnection->query($sql, $this->bindings);
    }

    public function first(): ?array
    {
        $this->limit(1);
        $results = $this->get();
        return $results[0] ?? null;
    }
}