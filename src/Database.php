<?php

namespace App;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $pdo = null;

    public static function getConnection(): PDO
    {
        if (self::$pdo === null) {
            $host = Config::get('DB_HOST', '127.0.0.1');
            $port = Config::get('DB_PORT', '3306');
            $dbname = Config::get('DB_NAME', 'twinsoniceforum');
            $user = Config::get('DB_USER', 'root');
            $pass = Config::get('DB_PASS', '');

            $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            try {
                self::$pdo = new PDO($dsn, $user, $pass, $options);
            } catch (PDOException $e) {
                // If database doesn't exist, try connecting without dbname and create it
                $dsnNoDb = "mysql:host={$host};port={$port};charset=utf8mb4";
                $tempPdo = new PDO($dsnNoDb, $user, $pass, $options);
                $tempPdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbname}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                self::$pdo = new PDO($dsn, $user, $pass, $options);
            }
        }

        return self::$pdo;
    }
}
