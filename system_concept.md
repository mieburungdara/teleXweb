# System Concept (CodeIgniter 3)

The system is a monolithic web application built with CodeIgniter 3.

*   **Backend (CodeIgniter 3):**
    *   **Controllers:**
        *   `Api.php`: Provides API endpoints for bot interactions and AJAX calls from the frontend.
        *   `Admin.php`: A new controller, accessible only to admin users, to handle user management and display the analytics dashboard.
        *   `Notifications.php`: A new controller for users to manage their custom notification rules.
    *   **Models:**
        *   `File_model.php`: Contains database logic for the `files` table. It will perform `JOIN` operations with the `users` table to retrieve file metadata along with user information. All database interactions will primarily use CodeIgniter's Query Builder for security and readability.
        *   `User_model.php`: A new model to handle all database logic for the `users` table, such as inserting a new user or updating their information (`insert_or_update_user`). This model will also utilize CodeIgniter's Query Builder.
    *   **Views:**
        *   `file_list.php`: An HTML file styled with Bootstrap 5 that displays the main file table.
        *   `gallery_view.php`: A view to display images in a grid format.
        *   `file_detail_view.php`: A dedicated page to show all details for a single file.
        *   `templates/`: A directory for header, footer, and other layout partials.
*   **Frontend (Bootstrap 5):**
    *   The frontend is not a separate SPA but is rendered by CodeIgniter's view engine.
    *   It will consist of a single page displaying the file list in a responsive table.
    *   JavaScript (potentially with jQuery, as it's common with CI3) will be used for any dynamic interactions if needed, but the primary goal is a server-rendered page.

## System Robustness and Performance

To ensure the application is reliable and efficient, the following concepts will be implemented:

*   **Scheduled Tasks (Cron Jobs):**
    *   A CodeIgniter controller will be created specifically to be run from the command line (CLI).
    *   This controller will contain methods for `cleanup_soft_deletes()` and `generate_reports()`.
    *   A server cron job will be configured to periodically call these controller methods.
*   **Error Handling & Retry Queue:**
    *   The webhook will have robust error handling. For transient errors (like a temporary database outage), the failed request's payload will be pushed into a simple queue (e.g., a dedicated database table).
    *   A separate cron job will process this queue, attempting to re-process the failed requests.
*   **Caching:**
    *   **Database Caching:** CodeIgniter's database caching driver will be enabled to cache results of frequent, non-critical queries (e.g., user list, statistics).
    *   **Page Caching:** For static or public-facing pages, CodeIgniter's web page caching will be used to serve pre-rendered HTML files for maximum speed.

