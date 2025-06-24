<?php
namespace src\Models;

use src\Database;
use src\QueryBuilders\QueryBuilder;

abstract class Model
{
    protected const string TABLE = '';
    protected Database $db;

    protected string $table;
    protected array $fillable = [];
    protected string $primaryKey = 'id';

    public function __construct()
    {
        $this->db = Database::getInstance(DB_HOST, DB_NAME, DB_USER, DB_PASS);
    }

    public function query(): QueryBuilder
    {
        $table = static::TABLE;
        $builderClass = 'src\\QueryBuilders\\' . ucfirst($table);
        return new $builderClass();
    }

    public function create(array $attributes): static
    {
        $model = new static();
        $attributes = array_intersect_key($attributes, array_flip($model->fillable));
        $id = static::query()->insert($attributes);
        return $this->find($id);
    }

    public function find($id): ?static
    {
        $model = new static();
        $data = static::query()
            ->where($model->primaryKey, '=', $id)
            ->first();
        if ($data) {
            $instance = new static();
            foreach ($data as $key => $value) {
                $instance->$key = $value;
            }
            return $instance;
        }
        return null;
    }

    public function update(array $attributes): bool
    {
        $attributes = array_intersect_key($attributes, array_flip($this->fillable));
        return static::query()
            ->where($this->primaryKey, '=', $this->{$this->primaryKey})
            ->update($attributes);
    }

    public function delete(): bool
    {
        return static::query()
            ->where($this->primaryKey, '=', $this->{$this->primaryKey})
            ->delete();
    }

    public function getTableColumns(): array
    {
        $sql = "SHOW COLUMNS FROM `" . static::TABLE . "`";
        $columns = $this->db->query($sql);
        return array_column($columns, 'Field');
    }

    public function tablesExist(): bool
    {
        $sql = "SHOW TABLES LIKE '" . static::TABLE . "'";
        return !empty($this->db->query($sql));
    }

}