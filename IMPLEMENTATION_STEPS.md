# Implementation Steps

This document outlines the planned sequence of development for the teleXweb project.

### Phase 1: Project & Database Setup

1.  **Install CodeIgniter 3:** Download and set up the basic framework.
2.  **Install Dependencies:** Use Composer to install third-party libraries (`phpdotenv`, Telegram Bot SDK).
3.  **Environment Configuration:** Create the `.env` file from a `.env.example` and configure `application/config/config.php` and `database.php` to use environment variables.
4.  **Database Creation:** Create the MySQL database.
5.  **Run Schema:** Execute the `schema.sql` file to create the `users` and `files` tables.

### Phase 2: Backend API & Bot Logic

1.  **Create Models:** Develop `User_model.php` and `File_model.php` with methods to interact with the database using the Query Builder.
2.  **Create API Controller:** Develop `application/controllers/api/Upload.php` to handle incoming metadata from the bot, validate data, and use the models to save it.
3.  **Implement Webhook Script:** Create the `webhook.php` script that receives updates from Telegram, extracts metadata, and forwards it to the CodeIgniter API endpoint.
4.  **Implement Bot Commands Logic:** Add logic to the webhook (or a dedicated bot controller) to handle commands like `/start`, `/help`, `/recent`, and `/search`.

### Phase 3: Frontend Web Interface

1.  **Create Base Templates:** Set up header, footer, and main layout templates in `application/views/templates/`.
2.  **Create Files Controller:** Develop `application/controllers/Files.php` to fetch file metadata using `File_model` (with JOINs to get user data).
3.  **Develop Files View:** Create `application/views/file_list.php` to display the data in a table.
4.  **Implement UI/UX Features:**
    *   Add logic to display file-type icons.
    *   Implement thumbnail display in the main table view.
    *   Develop the Gallery View page (`gallery_view.php`) and the corresponding controller method.
    *   Develop the File Detail page (`file_detail_view.php`) and its controller method.
    *   Add JavaScript for bulk actions (checkboxes, action buttons).
    *   Implement soft-delete functionality via a controller method.

### Phase 4: Advanced Features & Deployment

1.  **Implement Cron Jobs:** Create a CLI controller (`application/controllers/cli/Tasks.php`) for cleanup and reporting tasks. Configure server cron jobs.
2.  **Implement Caching:** Enable and configure database and page caching where appropriate.
3.  **Implement Web Security:** Set up authentication for the web interface.
4.  **Testing:** Write unit tests for models and perform manual testing of the end-to-end flow.
5.  **Deployment:** Plan and execute deployment to the production server.
