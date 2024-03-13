<?php

require __DIR__ . '/../vendor/autoload.php'; // Assuming you're autoloading classes with Composer
require __DIR__ . '/../src/Database/DatabaseConnection.php'; // Path to your DatabaseConnection class
require __DIR__ . '/../src/Logging/Logger.php';

// Create a new Logger instance, specifying the log file
$logger = new Logger('logfile.log');

// Use the DatabaseConnection class to get a PDO instance
$pdo = DatabaseConnection::connect();

// SQL statement for creating the `email_accounts` table
$sql = "
CREATE TABLE IF NOT EXISTS email_accounts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    imap_host VARCHAR(255) NOT NULL,
    imap_port INT NOT NULL,
    telegram_chat_id VARCHAR(255) NOT NULL,
    last_checked TIMESTAMP NULL DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
";

// Execute the SQL statement to create the table
try {
    $pdo->exec($sql);
    // Log the successful creation of the table
    $logger->info("Table 'email_accounts' created successfully.");
    echo "Table 'email_accounts' created successfully.\n";
} catch (PDOException $e) {
    // Log the error if table creation fails
    $logger->error("Could not create table 'email_accounts': " . $e->getMessage());
    die("Could not create table 'email_accounts': " . $e->getMessage() . "\n");
}
