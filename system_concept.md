# System Concept (CodeIgniter 3)

The system is a monolithic web application built with CodeIgniter 3.

*   **Backend (CodeIgniter 3):**
    *   **Controllers:**
        *   `Files.php`: Handles web requests to display the list of file metadata.
        *   `Api.php`: Provides an endpoint (`/api/upload`) to receive file metadata from the Telegram bot script. This controller will be responsible for validating the request and inserting the metadata into the `files` table.
    *   **Models:**
        *   `File_model.php`: Contains all database logic for interacting with the `files` table (e.g., `get_all_files`, `insert_file`).
    *   **Views:**
        *   `file_list.php`: An HTML file styled with Bootstrap 5 that displays the file table. It will fetch data from the `Files` controller.
*   **Frontend (Bootstrap 5):**
    *   The frontend is not a separate SPA but is rendered by CodeIgniter's view engine.
    *   It will consist of a single page displaying the file list in a responsive table.
    *   JavaScript (potentially with jQuery, as it's common with CI3) will be used for any dynamic interactions if needed, but the primary goal is a server-rendered page.
