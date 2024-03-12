<?php

require 'vendor/autoload.php'; // Include the Composer autoloader

// Include the database configuration file
$config = require 'dbconfig.php';

// Attempt to connect to the database using PDO
try {
    $pdo = new PDO(
        "mysql:host={$config['databaseHost']};dbname={$config['databaseName']}", 
        $config['databaseUsername'], 
        $config['databasePassword']
    );
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // If connection fails, stop the script and show the error
    die("Could not connect to the database {$config['databaseName']} :" . $e->getMessage());
}

// Fetch all email accounts from the database
$emailAccounts = $pdo->query("SELECT * FROM email_accounts")->fetchAll(PDO::FETCH_OBJ);

// Iterate through each email account
foreach ($emailAccounts as $account) {
    // Configure the IMAP connection parameters for the current account
    $mailbox = new PhpImap\Mailbox(
        '{' . $account->imap_host . ':' . $account->imap_port . '/imap/ssl}INBOX',
        $account->email,
        $account->password,
        null, // Directory for saving attachments (optional)
        'UTF-8' // Server encoding (optional)
    );

    try {
        // Search for new emails since the last checked date
        $emails = $mailbox->searchMailbox('SINCE "' . date('d-M-Y', strtotime($account->last_checked)) . '"');
    } catch (Exception $ex) {
        // Log any exception that occurs during the IMAP connection
        error_log('IMAP connection failed: ' . $ex->getMessage());
        continue;
    }

    if (!$emails) {
        // If no new emails are found, print a message and skip to the next account
        echo "No emails found for account: {$account->email}\n";
        continue;
    }

    // Process each email found
    foreach ($emails as $mailId) {
        $email = $mailbox->getMail($mailId);

        // Prepare the text to be sent via Telegram
        $text = "From: {$email->fromAddress}\nSubject: {$email->subject}\n{$email->textPlain}";

        // Initialize cURL session to send the email content to a Telegram bot
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.telegram.org/bot{$account->telegram_bot_token}/sendMessage");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'chat_id' => $account->telegram_chat_id,
            'text' => $text,
        ]));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch); // Execute the cURL session
        curl_close($ch); // Close the cURL session

        // Print a message indicating the email has been sent to Telegram
        echo "Email sent to Telegram for account: {$account->email}\n";
    }

    // Update the 'last_checked' time for the current account in the database
    $statement = $pdo->prepare("UPDATE email_accounts SET last_checked = NOW() WHERE id = :id");
    $statement->execute(['id' => $account->id]);
}
