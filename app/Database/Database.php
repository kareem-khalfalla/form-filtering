<?php

namespace App\Database;

use PDO;
use PDOException;
use PDOStatement;

abstract class Database
{
    protected PDO $pdo;

    protected $lastInsertId = null;

    protected array $mode = [];

    /**
     * @var array $defaultOptions
     */
    protected $defaultOptions = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ];

    /**
     * Merge constructor options.
     *
     * @param  array $options
     * 
     * @return array
     */
    public function constructOptions(array $options = []): array
    {
        if (empty($options)) {
            return $this->defaultOptions;
        }

        $defOps = $this->defaultOptions;
        foreach ($options as $key => $value) {
            $defOps[$key] = $value;
        }

        return $defOps;
    }

    /**
     * Delegates method call to underlying PDO object instance method.
     *
     * @param  mixed $attribute
     * @param  mixed $value
     * 
     * @return void
     */
    // public function setAttribute($attribute, $value): void
    // {
    //     $this->pdo->setAttribute($attribute, $value);
    // }

    /**
     * Execute sql statement.
     *
     * @param  string $sql
     * @param  array  $params
     * @param  array  $driverOptions
     * @param  bool   $return
     * 
     * @return bool|PDOStatement
     */
    public function execute(string $sql, array $params = [], array $driverOptions = [], bool $return = true): bool|PDOStatement
    {
        $stmt = $this->stmt($sql, $driverOptions);
        $result = $stmt->execute($params);

        if ($return) {
            return $result;
        }

        return $stmt;
    }

    /**
     * Prepare sql statement.
     *
     * @param  string $sql
     * @param  array  $driverOptions
     * @throws PDOException
     * @return PDOStatement
     */
    private function stmt(string $sql, array $driverOptions): PDOStatement
    {
        $stmt = $this->pdo->prepare($sql, $driverOptions);

        if (!$stmt) {
            throw new PDOException("Query statement failed.");
        }

        return $stmt;
    }

    /**
     * Insert new record into db.
     *
     * @param  string $table
     * @param  array $data
     * @return bool
     */
    public function insert(string $table, array $data): bool
    {
        $sql = "
            INSERT INTO `$table`
            (" . (implode(', ', array_keys($data))) . ")
            VALUES
            (" . implode(', ', array_fill(0, count($data), '?')) . ")
        ";

        if (!$this->execute($sql, array_values($data))) {
            return false;
        }

        $this->lastInsertId = $this->pdo->lastInsertId();

        return true;
    }

    /**
     * lastInsertId
     *
     * 
     */
    public function lastInsertId()
    {
        return $this->lastInsertId;
    }

    /**
     * update
     *
     * @param  string $table
     * @param  $id
     * @param  array $data
     * @param  string $where
     * 
     * @return bool|PDOStatement
     */
    public function update(string $table, $id, array $data = [], string $column = 'id'): bool|PDOStatement
    {
        $values = [];

        $sql = "UPDATE `$table` SET";

        foreach ($data as $key => $value) {
            $sql .= " $key = ?";
            $values[] = $value;
        }

        $sql .= " WHERE `$column` = ?";

        $values[] = $id;

        return $this->execute($sql, $values);
    }

    /**
     * fetchObject
     *
     * @param  string $sql
     * @param  mixed  $params
     * @param  string $className
     * @param  array  $constructorArgs
     * @return bool|object
     */
    public function fetchObject(string $sql, mixed $params = null, string $className = 'stdCLass', array $constructorArgs = []): bool|object
    {
        $stmt = $this->query($sql, [$params]);

        $this->statementFetchMode($stmt, [
            PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
            $className,
            $constructorArgs
        ]);

        return $stmt->fetch();
    }

    /**
     * statementFetchMode
     *
     * @param  PDOStatement $stmt
     * @param  array $mode
     * @return bool
     */
    private function statementFetchMode(PDOStatement $stmt, array $mode = []): bool
    {
        if (!empty($this->mode)) {
            $mode = $this->mode;
        }

        return call_user_func_array([$stmt, 'setFetchMode'], $mode);
    }

    /**
     * query
     *
     * @param  string $sql
     * @param  array  $params
     * @param  array  $driverOptions
     * @return PDOStatement
     */
    public function query(string $sql, array $params = [], array $driverOptions = []): PDOStatement
    {
        return $this->execute($sql, $params, $driverOptions, false);
    }

    /**
     * Delete record(s).
     *
     * @param  string $table
     * @param  int    $id
     * @param  string $field
     * @return bool|PDOStatement
     */
    public function delete(string $table, int $id, string $field = 'id'): bool|PDOStatement
    {
        $sql = "DELETE FROM `$table` WHERE `$field` = ?";

        return $this->execute($sql, [$id]);
    }

    /**
     * Count of all records.
     *
     * @param  string $table
     * @param  string $where
     * @param  mixed  $params
     * 
     * @return int
     */
    public function count(string $table, string $where = null, mixed $params = null): int
    {
        $sql = "SELECT COUNT(*) AS `number` FROM `$table`";

        if (!is_null($where)) {
            $sql .= " $where";
        }

        return $this->query($sql, (array) $params)->fetch()['number'];
    }

    /**
     * fetchObjects
     *
     * @param  string $sql
     * @param  mixed  $params
     * @param  string $className
     * @param  array  $constructorArgs
     * @return array
     */
    public function fetchObjects(string $sql, mixed $params = null, string $className = 'stdCLass', array $constructorArgs = []): array
    {
        $stmt = $this->query($sql, (array) $params);

        $this->statementFetchMode($stmt, [
            PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
            $className,
            $constructorArgs
        ]);

        return $stmt->fetchAll();
    }
}
