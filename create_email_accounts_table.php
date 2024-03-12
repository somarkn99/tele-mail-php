<?php

require 'vendor/autoload.php'; // Assuming you're autoloading classes with Composer
require 'DatabaseConnection.php'; // Path to your DatabaseConnection class

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
    echo "Table 'email_accounts' created successfully.\n";
} catch (PDOException $e) {
    die("Could not create table 'email_accounts': " . $e->getMessage() . "\n");
}
