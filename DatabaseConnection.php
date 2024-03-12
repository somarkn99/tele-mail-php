<?php

// Include Composer's autoload file to automatically load required classes, libraries, or files.
// This makes it easier to manage dependencies and autoload classes in your project.
require 'vendor/autoload.php';

// Define the DatabaseConnection class.
// This class is responsible for creating and managing the connection to your database.
class DatabaseConnection {
    // Define a public static method named connect.
    // Static methods can be called without an instance of the class.
    // This method will attempt to connect to the database and return the connection object.
    public static function connect() {
        // Load database configuration settings from the dbconfig.php file.
        // This file should return an array containing database connection parameters.
        $config = require 'dbconfig.php';

        try {
            // Attempt to create a new PDO (PHP Data Objects) instance to represent the connection to the database.
            // The PDO constructor accepts parameters for the DSN (Data Source Name), username, and password.
            $pdo = new PDO(
                "mysql:host={$config['databaseHost']};dbname={$config['databaseName']}", 
                $config['databaseUsername'], 
                $config['databasePassword']
            );

            // Set the PDO error mode to exception.
            // This tells PDO to throw exceptions for any database errors, making error handling easier.
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // If the connection is successful, return the PDO instance.
            return $pdo;
        } catch (PDOException $e) {
            // If there is an error connecting to the database, terminate the script and output an error message.
            // The PDOException object contains details about the error.
            die("Could not connect to the database {$config['databaseName']} :" . $e->getMessage());
        }
    }
}
