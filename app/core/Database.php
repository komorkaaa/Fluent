<?php

namespace App\Core;

use PDO;

class Database {
    private static ?PDO $connection = null;

    public static function connection(): PDO {
        if (self::$connection === null) {
            $host = getenv('DB_HOST');
            $port = getenv('DB_PORT');
            $database = getenv('DB_NAME');
            $user = getenv('DB_USER');
            $password = getenv('DB_PASSWORD');

            $dsn = "pgsql:host={$host};port={$port};dbname={$database}";

            self::$connection = new PDO($dsn, $user, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        }

        return self::$connection;
    }
}