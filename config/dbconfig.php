<?php

// Use Dotenv to load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Return the configuration as an array
return [
    'databaseHost' => $_ENV['DATABASE_HOST'],
    'databaseName' => $_ENV['DATABASE_NAME'],
    'databaseUsername' => $_ENV['DATABASE_USER'],
    'databasePassword' => $_ENV['DATABASE_PASS'],
];
