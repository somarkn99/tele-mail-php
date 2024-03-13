<?php

class Logger {
    // Path to the log file
    protected $logFile;

    public function __construct($logFile = 'logfile.log') {
        $this->logFile = $logFile;
    }

    // Write a message to the log file
    public function log($message, $level = 'INFO') {
        // Construct the log message
        $logMessage = '[' . date('Y-m-d H:i:s') . '] ' . $level . ': ' . $message . PHP_EOL;

        // Write the log message to the log file
        file_put_contents($this->logFile, $logMessage, FILE_APPEND);
    }

    // Shortcut function for logging errors
    public function error($message) {
        $this->log($message, 'ERROR');
    }

    // Shortcut function for logging generic info
    public function info($message) {
        $this->log($message, 'INFO');
    }
}
