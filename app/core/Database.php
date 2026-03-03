<?php

namespace App\Core;

use mysqli;

class Database
{
    private static ?mysqli $connection = null;

    public static function connection(): mysqli
    {
        if (self::$connection instanceof mysqli) {
            return self::$connection;
        }

        $config = require APP_PATH . '/config/database.php';

        self::$connection = new mysqli(
            $config['host'],
            $config['username'],
            $config['password'],
            $config['database']
        );

        if (self::$connection->connect_errno) {
            throw new \RuntimeException('Failed to connect database: ' . self::$connection->connect_error);
        }

        return self::$connection;
    }
}
