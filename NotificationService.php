<?php

// Include and initialize the Dotenv library to load environment variables from the .env file.
// This allows you to use environment variables for configuration settings, improving security and flexibility.
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Define the NotificationService class.
// This class is responsible for sending notifications via Telegram's bot API.
class NotificationService {

    // Define a public static method to send messages to Telegram.
    // This method can be called without instantiating the NotificationService class.
    public static function sendToTelegram($chatId, $message) {
        // Retrieve the Telegram bot token from environment variables.
        // This token is necessary for authenticating requests to the Telegram API.
        $botToken = $_ENV['TELEGRAM_BOT_TOKEN'];

        // Initialize a cURL session to make an HTTP request to the Telegram API.
        $ch = curl_init();

        // Set the URL for the cURL request, including the bot token.
        // The URL is the endpoint for sending messages through the Telegram bot API.
        curl_setopt($ch, CURLOPT_URL, "https://api.telegram.org/bot{$botToken}/sendMessage");

        // Specify that the request method should be POST.
        curl_setopt($ch, CURLOPT_POST, 1);

        // Set the POST fields for the cURL request.
        // This includes the chat ID where the message will be sent and the text of the message.
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'chat_id' => $chatId,
            'text' => $message,
        ]));

        // Set an option to return the response as a string instead of outputting it directly.
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute the cURL session and store the response.
        // This sends the message to the specified chat through the Telegram bot.
        $response = curl_exec($ch);

        // Close the cURL session to free up system resources.
        curl_close($ch);

        // Return the response from the Telegram API.
        // This could be used for error handling or logging if desired.
        return $response;
    }
}
