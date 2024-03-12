<?php

// Include the necessary files for database connection and notification service
require 'DatabaseConnection.php';
require 'NotificationService.php'; // Ensure NotificationService is included
require 'Logger.php'; // Include the Logger class

// Define the EmailProcessor class
class EmailProcessor {
    protected $pdo; // Property to hold the PDO database connection object
    protected $logger; // Property to hold the Logger object

    // Constructor method to establish database connection upon class instantiation
    public function __construct() {
        // Establish the database connection using the DatabaseConnection class
        $this->pdo = DatabaseConnection::connect();
        $this->logger = new Logger('logfile.log'); // Initialize the Logger object
    }

    // Method to fetch all email accounts from the database
    public function fetchEmailAccounts() {
        // Execute a SELECT query to fetch all records from the email_accounts table
        // Returns an array of objects, each representing an email account
        return $this->pdo->query("SELECT * FROM email_accounts")->fetchAll(PDO::FETCH_OBJ);
    }

    // Method to process emails for each account fetched from the database
    public function processEmails($emailAccounts) {
        // Iterate through each email account object
        foreach ($emailAccounts as $account) {
            // Initialize a new PhpImap\Mailbox instance for interacting with the email account
            // Configuration includes connection parameters such as host, port, email, and password
            $mailbox = new PhpImap\Mailbox(
                '{' . $account->imap_host . ':' . $account->imap_port . '/imap/ssl}INBOX', // Connection string
                $account->email, // Email address for the account
                $account->password, // Password for the email account
                null, // Optional directory for saving attachments
                'UTF-8' // Character encoding
            );

            try {
                // Attempt to search for new emails in the mailbox since the last checked date
                $emails = $mailbox->searchMailbox('SINCE "' . date('d-M-Y', strtotime($account->last_checked)) . '"');
            } catch (Exception $ex) {
                // Log any exceptions that occur, indicating a failure to connect or search the mailbox
                $this->logger->error('IMAP connection failed for account ' . $account->email . ': ' . $ex->getMessage());
                continue; // Skip to the next email account on failure
            }

            if (!$emails) {
                // If no new emails are found, output a message and continue to the next account
                $this->logger->info("No emails found for account: {$account->email}");
                continue;
            }

            // If new emails are found, process each email
            foreach ($emails as $mailId) {
                // Fetch the email details using its ID
                $email = $mailbox->getMail($mailId);

                // Prepare the notification message with email details
                $text = "From: {$email->fromAddress}\nSubject: {$email->subject}\n{$email->textPlain}";

                // Send the prepared message to Telegram using the NotificationService
                NotificationService::sendToTelegram(
                    $account->telegram_chat_id,
                    $text
                );

                // Output a confirmation message
                $this->logger->info("Email sent to Telegram for account: {$account->email}");
                echo "Email sent to Telegram for account: {$account->email}\n";
            }

            // After processing emails, update the 'last_checked' time in the database for this account
            $statement = $this->pdo->prepare("UPDATE email_accounts SET last_checked = NOW() WHERE id = :id");
            $statement->execute(['id' => $account->id]);
        }
    }
}
