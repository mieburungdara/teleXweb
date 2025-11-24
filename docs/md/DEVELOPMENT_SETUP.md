# Development Setup Guide

This document covers the planned project structure and third-party dependencies.

## Planned Directory Structure

The following structure will be used within the `application/` directory of CodeIgniter 3 to keep the project organized.

```
application/
├── controllers/
│   ├── api/
│   │   └── Upload.php      # Controller for API endpoints
│   ├── cli/
│   │   └── Tasks.php       # Controller for cron jobs
│   └── Files.php           # Controller for the main web interface
├── core/
├── helpers/
├── hooks/
├── language/
├── libraries/              # For custom or third-party libraries
├── models/
│   ├── File_model.php
│   └── User_model.php
├── third_party/
└── views/
    ├── templates/
    │   ├── header.php
    │   └── footer.php
    └── file_list.php
```

## Third-Party Libraries

The following PHP libraries will be installed via Composer.

1.  **`vlucas/phpdotenv`**: To manage environment variables for configuration (database credentials, API keys, etc.). This allows for different settings in development and production without changing code.
2.  **`telegram-bot-sdk` (or similar)**: A comprehensive library to interact with the Telegram Bot API. This simplifies tasks like sending messages, handling commands, and processing updates, making the code cleaner and more reliable than using manual cURL requests. A specific library (e.g., `irazasyed/telegram-bot-sdk`) will be chosen at the time of implementation.
