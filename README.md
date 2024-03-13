# Email Processing System

This project is a PHP-based system for processing emails and sending notifications via Telegram. It provides functionality to connect to a database, fetch email accounts, process incoming emails, and send notifications using the Telegram API.

## Create Telegram Bot
1. Open the Telegram app and search for the "BotFather" user.
2. Start a conversation with BotFather by clicking on it and then click on the "Start" button.
3. Send the command /newbot to create a new bot.
4. Follow the prompts to provide a name for your bot and a username (ending in "bot").
5. Once your bot is created, BotFather will provide you with a token. Copy this token as you'll need it later.

## Installation

1. Clone the repository to your local machine:

```bash
git clone https://github.com/somarkn99/tele-mail-php.git
```

2. Install dependencies using Composer:

```bash
composer install
```

3. Set up environment variables by creating a `.env` file based on the provided `.env.example` file. Modify the variables to match your environment.

4. Set up a cron job or task scheduler to run the index.php script at regular intervals to process emails. For example, to run the script every 5 minutes:

```bash
*/5 * * * * php /path/to/your/project/public/index.php
```
5. Run the Database Creation Script:

```bash
php scripts/create_database.php
```

## Usage

1. Define email accounts in the database by running the create_email_accounts_table.php script. This will create the necessary database table.

2. Populate the email_accounts table with the details of the email accounts you want to monitor.

3. Access the system through your web browser to trigger email processing manually or ensure that the cron job/task scheduler is running to process emails automatically.

## Features
1. Fetch email accounts from a database.
2. Process incoming emails and send notifications to Telegram.
3. Error logging and handling.
4. Environment variable configuration using Dotenv.

## Contributors
- [Somar kesen](https://www.linkedin.com/in/somarkesen/)
