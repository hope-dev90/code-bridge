<?php

class Database {
    private static $connection = null;

    public static function getConnection() {
        if (self::$connection === null) {
            // From cPanel > MySQL Databases, after creating the DB/user for FEMS.
            // cPanel usually prefixes both with your account username, e.g.
            // cpaneluser_fems_db / cpaneluser_fems_user
            $host = 'localhost';
            $db   = 'cpaneluser_fems_db';
            $user = 'fems';
            $pass = 'Fems_Password+250';

            try {
                self::$connection = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (\PDOException $e) {
                die("Connection failed: " . $e->getMessage());
            }
        }

        return self::$connection;
    }
}
