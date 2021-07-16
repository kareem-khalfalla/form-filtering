<?php

namespace App\Database;

use App\Database\Driver\MySQL;
use App\Database\Driver\SQLite;

class DatabaseManager
{

    /**
     * make
     *
     * @param   $driver
     * @param  array  $options
     * 
     * @return Database
     */
    public static function make($driver = null, array $options = []): Database
    {
        if (is_null($driver)) {
            $driver = $_ENV['DB_CONNECTION'];
        }

        // return call_user_func([new static, $driver], $options);
        $new = new self;
        return $new::$driver($options);
    }

    /**
     * sqlite
     *
     * @param  array $options
     * @return Database
     */
    protected static function sqlite(array $options = []): Database
    {
        return new SQLite($_ENV['DB_CONNECTION'], $options);
    }

    /**
     * mysql
     *
     * @param  array $options
     * @return Database
     */
    protected static function mysql(array $options = []): Database
    {
        return new MySQL(
            $_ENV['DB_HOST'],
            $_ENV['DB_PORT'],
            $_ENV['DB_DATABASE'],
            $_ENV['DB_USERNAME'],
            $_ENV['DB_PASSWORD'],
            $options
        );
    }
}
