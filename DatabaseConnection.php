<?php

require 'vendor/autoload.php';

class DatabaseConnection {
    public static function connect() {
        $config = require 'dbconfig.php';
        try {
            $pdo = new PDO(
                "mysql:host={$config['databaseHost']};dbname={$config['databaseName']}", 
                $config['databaseUsername'], 
                $config['databasePassword']
            );
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            die("Could not connect to the database {$config['databaseName']} :" . $e->getMessage());
        }
    }
}
