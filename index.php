<?php

require 'EmailProcessor.php';

$emailProcessor = new EmailProcessor();
$emailAccounts = $emailProcessor->fetchEmailAccounts();
$emailProcessor->processEmails($emailAccounts);
