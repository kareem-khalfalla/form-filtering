<?php

namespace App;

use Exception;

class Container
{
    private static array $container = [];

    /**
     * Bind to the container.
     *
     * @param  mixed $key
     * @param  mixed $value
     * @return void
     */
    public static function bind(mixed $key, mixed $value = null): void
    {
        if (!is_array($key)) {
            $key = [$key => $value];
        }

        self::bindArray($key);
    }

    /**
     * Get item from the container.
     *
     * @param  mixed $key
     * @return mixed
     */
    public static function get(mixed $key): mixed
    {
        if (!array_key_exists($key, self::$container)) {
            throw new Exception("There is not '$key' bound in the container.");
        }

        return self::$container[$key];
    }

    /**
     * Bind array to the container.
     *
     * @param  mixed $arr
     * @return void
     */
    public static function bindArray(array $arr): void
    {
        self::$container = array_merge(self::$container, $arr);
    }
}
