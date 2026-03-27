<?php
/**
 * TSILIZY CORE - Database Class
 * PDO wrapper for database operations
 */

class Database
{
    private static ?PDO $instance = null;
    private static int $queryCount = 0;

    /**
     * Get database connection instance (Singleton)
     */
    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            try {
                $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
                self::$instance = new PDO($dsn, DB_USER, DB_PASS, DB_OPTIONS);
            } catch (PDOException $e) {
                error_log('Database Connection Error: ' . $e->getMessage());
                if (APP_ENV === 'development') {
                    throw new Exception('Database Connection Error: ' . $e->getMessage());
                } else {
                    throw new Exception('Database connection failed. Please try again later.');
                }
            }
        }
        return self::$instance;
    }

    /**
     * Execute a query with optional parameters
     */
    public static function query(string $sql, array $params = []): PDOStatement
    {
        self::$queryCount++;
        $stmt = self::getInstance()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    /**
     * Fetch all rows
     */
    public static function fetchAll(string $sql, array $params = []): array
    {
        return self::query($sql, $params)->fetchAll();
    }

    /**
     * Fetch single row
     */
    public static function fetch(string $sql, array $params = []): ?array
    {
        $result = self::query($sql, $params)->fetch();
        return $result ?: null;
    }

    /**
     * Fetch single column value
     */
    public static function fetchColumn(string $sql, array $params = []): mixed
    {
        return self::query($sql, $params)->fetchColumn();
    }

    /**
     * Insert a new row
     */
    public static function insert(string $table, array $data): int
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        
        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
        self::query($sql, array_values($data));
        
        return (int) self::getInstance()->lastInsertId();
    }

    /**
     * Update existing rows
     */
    public static function update(string $table, array $data, string $where, array $whereParams = []): int
    {
        $set = implode(' = ?, ', array_keys($data)) . ' = ?';
        $sql = "UPDATE {$table} SET {$set} WHERE {$where}";
        
        $params = array_merge(array_values($data), $whereParams);
        return self::query($sql, $params)->rowCount();
    }

    /**
     * Delete rows
     */
    public static function delete(string $table, string $where, array $params = []): int
    {
        $sql = "DELETE FROM {$table} WHERE {$where}";
        return self::query($sql, $params)->rowCount();
    }

    /**
     * Soft delete (archive)
     */
    public static function archive(string $table, int $id, ?int $userId = null): bool
    {
        $data = [
            'is_archived' => 1,
            'archived_at' => date(DATETIME_FORMAT)
        ];
        
        if ($userId !== null) {
            $data['archived_by'] = $userId;
        }
        
        return self::update($table, $data, 'id = ?', [$id]) > 0;
    }

    /**
     * Restore archived record
     */
    public static function restore(string $table, int $id): bool
    {
        $data = [
            'is_archived' => 0,
            'archived_at' => null,
            'archived_by' => null
        ];
        
        return self::update($table, $data, 'id = ?', [$id]) > 0;
    }

    /**
     * Count rows
     */
    public static function count(string $table, string $where = '1=1', array $params = []): int
    {
        $sql = "SELECT COUNT(*) FROM {$table} WHERE {$where}";
        return (int) self::fetchColumn($sql, $params);
    }

    /**
     * Check if record exists
     */
    public static function exists(string $table, string $where, array $params = []): bool
    {
        return self::count($table, $where, $params) > 0;
    }

    /**
     * Begin transaction
     */
    public static function beginTransaction(): bool
    {
        return self::getInstance()->beginTransaction();
    }

    /**
     * Commit transaction
     */
    public static function commit(): bool
    {
        return self::getInstance()->commit();
    }

    /**
     * Rollback transaction
     */
    public static function rollback(): bool
    {
        return self::getInstance()->rollBack();
    }

    /**
     * Get query count for debugging
     */
    public static function getQueryCount(): int
    {
        return self::$queryCount;
    }

    /**
     * Get last insert ID
     */
    public static function lastInsertId(): int
    {
        return (int) self::getInstance()->lastInsertId();
    }
}
