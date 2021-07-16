<?php

namespace App\Database\Driver;

use App\Database\Database;
use PDO;

class SQLite extends Database
{
    /**
     * __construct
     *
     * @param  string $database
     * @param  array $options
     * @return void
     */
    public function __construct(string $database, array $options = [])
    {
        $this->pdo = new PDO(
            "sqlite:$database",
            null,
            null,
            $this->constructOptions($options)
        );
    }
}
