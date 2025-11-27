# Deployment Plan

This document outlines the steps to deploy the teleXweb application to a production server.

## 1. Server Requirements

*   **Web Server:** Apache or Nginx with PHP support.
*   **PHP:** Version 7.4 or newer.
*   **Database:** MySQL or MariaDB.
*   **Composer:** For installing PHP dependencies.
*   **Git:** For cloning the repository.
*   **Cron:** For running scheduled tasks.

## 2. CodeIgniter Configuration

Before deploying, you need to configure the application for your production environment.

### a. Set Environment to Production

In `web/index.php`, change the `ENVIRONMENT` constant:

```php
define('ENVIRONMENT', 'production');
```

This will disable detailed error reporting and enable other production-specific features.

### b. Configure `config.php`

In `web/application/config/config.php`:

*   Set the `base_url` to your production domain:
    ```php
    $config['base_url'] = 'https://your-domain.com/';
    ```

*   Set a new, secure `encryption_key`. You can generate one from a random string generator.

### c. Configure `database.php`

In `web/application/config/database.php`:

*   Update the `hostname`, `username`, `password`, and `database` fields with your production database credentials.

## 3. Deployment Steps

1.  **Clone the Repository**

    On your server, clone the project repository:

    ```bash
    git clone https://github.com/mieburungdara/teleXweb.git
    cd teleXweb
    ```

2.  **Install Composer Dependencies**

    Navigate to the `web` directory and run Composer to install the required PHP libraries:

    ```bash
    cd web
    composer install --no-dev
    ```

3.  **Run Database Migrations**

    From the project root, run the CodeIgniter migrations to set up the database schema:

    ```bash
    php web/index.php migrate index
    ```

4.  **Set Up Cron Jobs**

    Set up cron jobs to run the cleanup tasks. Refer to the `docs/md/cron_jobs.md` file for detailed instructions.

5.  **Set the Telegram Bot Webhook**

    You need to tell Telegram where to send updates for your bot. You can do this by making a request to the Telegram Bot API:

    ```
    https://api.telegram.org/bot<YOUR_BOT_TOKEN>/setWebhook?url=https://your-domain.com/web/webhook.php/<YOUR_BOT_TELEGRAM_ID>
    ```

    Replace `<YOUR_BOT_TOKEN>`, `<YOUR_BOT_TELEGRAM_ID>`, and `https://your-domain.com` with your actual bot token, bot ID, and domain.

## 4. Post-Deployment

*   **Permissions:** Ensure that the `web/application/cache/` and `web/application/logs/` directories are writable by the web server.
*   **Security:** For a production environment, it's highly recommended to move the `system` and `application` directories outside of the web root for better security. This requires modifying the paths in `web/index.php`.
*   **HTTPS:** Ensure your site is served over HTTPS.

---
This concludes the basic deployment plan. Further steps may be required depending on your specific server configuration.
