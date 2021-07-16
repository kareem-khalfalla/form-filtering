<?php

namespace App\Database\Driver;

use App\Database\Database;
use PDO;

class MySQL extends Database
{
    /**
     * __construct
     *
     * @param  string $host
     * @param  string $port
     * @param  string $dbname
     * @param  string $username
     * @param  string $password
     * @param  array  $options
     * 
     * @return void
     */
    public function __construct(string $host, string $port, string $dbname, string $username, string $password, array $options = [])
    {
        $this->pdo = new PDO(
            "mysql:host=$host;port=$port;dbname=$dbname",
            $username,
            $password,
            $this->constructOptions($this->parseOptions($options))
        );
    }

    /**
     * Add options to PDO constrcutor.
     *
     * @param  array $options
     * @return array
     */
    private function parseOptions(array $options = []): array
    {
        if (!array_key_exists(PDO::MYSQL_ATTR_INIT_COMMAND, $options)) {
            $options[PDO::MYSQL_ATTR_INIT_COMMAND] = 'SET NAMES utf8mb4';
        }
        return $options;
    }
}
