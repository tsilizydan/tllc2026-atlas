<?php
/**
 * TSILIZY CORE - Base Model
 * Abstract base model for all entities
 */

abstract class Model
{
    protected static string $table = '';
    protected static string $primaryKey = 'id';
    protected static array $fillable = [];
    protected static bool $softDelete = false;

    /**
     * Get all records
     */
    public static function all(bool $includeArchived = false): array
    {
        $sql = "SELECT * FROM " . static::$table;
        
        if (static::$softDelete && !$includeArchived) {
            $sql .= " WHERE is_archived = 0";
        }
        
        $sql .= " ORDER BY " . static::$primaryKey . " DESC";
        
        return Database::fetchAll($sql);
    }

    /**
     * Find by primary key
     */
    public static function find(int $id): ?array
    {
        return Database::fetch(
            "SELECT * FROM " . static::$table . " WHERE " . static::$primaryKey . " = ?",
            [$id]
        );
    }

    /**
     * Find by primary key or fail
     */
    public static function findOrFail(int $id): array
    {
        $result = static::find($id);
        
        if (!$result) {
            Router::notFound();
        }
        
        return $result;
    }

    /**
     * Find by column value
     */
    public static function findBy(string $column, mixed $value): ?array
    {
        return Database::fetch(
            "SELECT * FROM " . static::$table . " WHERE {$column} = ?",
            [$value]
        );
    }

    /**
     * Get records matching conditions
     */
    public static function where(string $where, array $params = [], string $orderBy = null): array
    {
        $sql = "SELECT * FROM " . static::$table . " WHERE " . $where;
        
        if ($orderBy) {
            $sql .= " ORDER BY " . $orderBy;
        }
        
        return Database::fetchAll($sql, $params);
    }

    /**
     * Get first record matching conditions
     */
    public static function first(string $where, array $params = []): ?array
    {
        return Database::fetch(
            "SELECT * FROM " . static::$table . " WHERE " . $where . " LIMIT 1",
            $params
        );
    }

    /**
     * Create a new record
     */
    public static function create(array $data): int
    {
        // Filter to only fillable fields
        if (!empty(static::$fillable)) {
            $data = array_intersect_key($data, array_flip(static::$fillable));
        }
        
        // Add timestamps
        $data['created_at'] = date(DATETIME_FORMAT);
        $data['updated_at'] = date(DATETIME_FORMAT);
        
        return Database::insert(static::$table, $data);
    }

    /**
     * Update a record
     */
    public static function update(int $id, array $data): bool
    {
        // Filter to only fillable fields
        if (!empty(static::$fillable)) {
            $data = array_intersect_key($data, array_flip(static::$fillable));
        }
        
        // Add updated timestamp
        $data['updated_at'] = date(DATETIME_FORMAT);
        
        return Database::update(static::$table, $data, static::$primaryKey . " = ?", [$id]) > 0;
    }

    /**
     * Delete a record
     */
    public static function delete(int $id): bool
    {
        return Database::delete(static::$table, static::$primaryKey . " = ?", [$id]) > 0;
    }

    /**
     * Archive a record (soft delete)
     */
    public static function archive(int $id, ?int $userId = null): bool
    {
        if (!static::$softDelete) {
            return static::delete($id);
        }
        
        return Database::archive(static::$table, $id, $userId);
    }

    /**
     * Restore an archived record
     */
    public static function restore(int $id): bool
    {
        if (!static::$softDelete) {
            return false;
        }
        
        return Database::restore(static::$table, $id);
    }

    /**
     * Count records
     */
    public static function count(string $where = '1=1', array $params = []): int
    {
        if (static::$softDelete && strpos($where, 'is_archived') === false) {
            $where = "({$where}) AND is_archived = 0";
        }
        
        return Database::count(static::$table, $where, $params);
    }

    /**
     * Check if record exists
     */
    public static function exists(string $where, array $params = []): bool
    {
        return Database::exists(static::$table, $where, $params);
    }

    /**
     * Get paginated records
     */
    public static function paginate(int $page = 1, int $perPage = null, string $where = '1=1', array $params = []): array
    {
        $perPage = $perPage ?? ITEMS_PER_PAGE;
        
        if (static::$softDelete && strpos($where, 'is_archived') === false) {
            $where = "({$where}) AND is_archived = 0";
        }
        
        $total = static::count($where, $params);
        $pagination = paginate($total, $page, $perPage);
        
        $sql = "SELECT * FROM " . static::$table . " WHERE " . $where;
        $sql .= " ORDER BY " . static::$primaryKey . " DESC";
        $sql .= " LIMIT " . $pagination['per_page'] . " OFFSET " . $pagination['offset'];
        
        return [
            'data' => Database::fetchAll($sql, $params),
            'pagination' => $pagination
        ];
    }

    /**
     * Get archived records
     */
    public static function archived(): array
    {
        if (!static::$softDelete) {
            return [];
        }
        
        return Database::fetchAll(
            "SELECT * FROM " . static::$table . " WHERE is_archived = 1 ORDER BY archived_at DESC"
        );
    }

    /**
     * Search records
     */
    public static function search(array $columns, string $term, bool $includeArchived = false): array
    {
        $conditions = [];
        $params = [];
        
        foreach ($columns as $column) {
            $conditions[] = "{$column} LIKE ?";
            $params[] = "%{$term}%";
        }
        
        $where = '(' . implode(' OR ', $conditions) . ')';
        
        if (static::$softDelete && !$includeArchived) {
            $where .= " AND is_archived = 0";
        }
        
        return Database::fetchAll(
            "SELECT * FROM " . static::$table . " WHERE " . $where . " ORDER BY " . static::$primaryKey . " DESC",
            $params
        );
    }

    /**
     * Get distinct values for a column
     */
    public static function distinct(string $column): array
    {
        $results = Database::fetchAll(
            "SELECT DISTINCT {$column} FROM " . static::$table . " WHERE {$column} IS NOT NULL ORDER BY {$column} ASC"
        );
        
        return array_column($results, $column);
    }
}
