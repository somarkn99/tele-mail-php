<?php

require 'DatabaseConnection.php';
require 'NotificationService.php'; // Ensure NotificationService is included

class EmailProcessor {
    protected $pdo;

    public function __construct() {
        $this->pdo = DatabaseConnection::connect();
    }

    public function fetchEmailAccounts() {
        return $this->pdo->query("SELECT * FROM email_accounts")->fetchAll(PDO::FETCH_OBJ);
    }

    public function processEmails($emailAccounts) {
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

                // Use NotificationService to send the message to Telegram
                NotificationService::sendToTelegram(
                    $account->telegram_chat_id,
                    $text
                );

                // Print a message indicating the email has been sent to Telegram
                echo "Email sent to Telegram for account: {$account->email}\n";
            }

            // Update the 'last_checked' time for the current account in the database
            $statement = $this->pdo->prepare("UPDATE email_accounts SET last_checked = NOW() WHERE id = :id");
            $statement->execute(['id' => $account->id]);

        }
    }
}
