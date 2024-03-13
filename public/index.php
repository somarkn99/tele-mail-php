<?php

// Require or include the EmailProcessor class definition.
// This line ensures that the EmailProcessor class is loaded and available for use in this script.
require __DIR__ . '/../src/Email/EmailProcessor.php';

// Create a new instance of the EmailProcessor class.
// The EmailProcessor class is responsible for handling the email processing logic,
// including fetching email account details and processing each email.
$emailProcessor = new EmailProcessor();

// Call the fetchEmailAccounts method of the EmailProcessor instance.
// This method is designed to retrieve all email account records from the database.
// The retrieved email accounts are stored in the $emailAccounts variable as an array or collection of objects.
$emailAccounts = $emailProcessor->fetchEmailAccounts();

// Call the processEmails method of the EmailProcessor instance,
// passing the previously retrieved email accounts as an argument.
// This method processes each email account in turn. Processing may include checking for new emails,
// downloading them, sending notifications, or any other logic defined within the method.
$emailProcessor->processEmails($emailAccounts);
