# Project Plan: Telegram File Management Website

## Initial Request
The user wants to create a website to manage Telegram files sent by users.

## Proposed Plan (Initial - Rejected)
*   **Frontend:** React (JavaScript) with Bootstrap for styling and Material Design principles.
*   **Backend:** Node.js with Express.js (JavaScript) for the API.
*   **Telegram Bot:** Implemented using `node-telegram-bot-api`.
*   **Storage:** Local storage, metadata in a simple JSON file for prototype.

## Revised Plan (Accepted)

**Application Type:** Full-stack web application.

**Core Purpose:** Menerima metadata file yang dikirim oleh pengguna melalui bot Telegram, menyimpannya, dan menyediakan antarmuka web untuk melihat serta mengelola metadata file-file tersebut.

**Key Technologies:**
*   **Frontend:** HTML, CSS, and JavaScript with **Bootstrap 5** for a responsive and modern UI.
*   **Backend:** **CodeIgniter 3** (PHP) for routing, file storage, database interaction, and communication with the Telegram Bot API.
*   **Telegram Bot:** A separate PHP script or integrated within CodeIgniter to listen for incoming files from Telegram.
*   **Penyimpanan:** Hanya metadata file (nama file, ID pengguna Telegram, *timestamp*, `telegram_file_id`, dll.) yang akan disimpan dalam database MySQL. File fisik tidak akan disimpan di server.

**Main Features:**
1.  **Penerimaan & Penyimpanan Metadata File:** Backend akan menerima dan menyimpan metadata file (termasuk `thumbnail_file_id`) dari bot ke dalam database MySQL.
2.  **Perintah Interaktif Bot:** Bot akan mendukung perintah seperti `/start`, `/help`, `/recent`, dan `/search` untuk interaksi pengguna yang lebih baik.
3.  **Antarmuka Web Daftar File:** Halaman web yang menampilkan metadata file dengan ikon tipe file, pratinjau gambar (thumbnail), dan detail lainnya.
4.  **Aksi Massal (Bulk Actions):** Antarmuka web akan menyediakan fungsionalitas untuk memilih banyak item dan melakukan tindakan massal (misalnya, soft delete).
5.  **Soft Deletion:** Kemampuan untuk menghapus catatan metadata secara "lunak" (soft delete) dari antarmuka web.

**General Approach:**
Saya akan menyiapkan proyek CodeIgniter 3 yang akan berfungsi sebagai backend API dan juga melayani halaman frontend. Bot Telegram (skrip PHP) akan mengirimkan metadata file yang diterima ke endpoint API di CodeIgniter. Frontend yang menggunakan Bootstrap 5 akan memanggil API CodeIgniter untuk mendapatkan daftar metadata file.

### Conceptual Setup Steps

1.  **CodeIgniter 3 Download & Extraction:** Obtain the framework from its official source and extract it into the project directory.
2.  **Basic Project Structure:** Understand the `application`, `system`, and `user_guide` directories, with primary development in `application`.
3.  **Initial Configuration:**
    *   `application/config/config.php`: Configure `base_url`, `index_page`, etc.
    *   `application/config/database.php`: Set up MySQL database connection details.
    *   `application/config/autoload.php`: Define libraries, helpers, and models for automatic loading.
4.  **Telegram Bot (PHP Script) Concept:** A separate PHP script will act as a Telegram webhook/long polling client, receiving updates (including files) and forwarding them to a CodeIgniter API endpoint.
5.  **MySQL Database Setup Concept:** Create a MySQL database with two tables: `telegram_files` to store file metadata and a `users` table to store user information. A foreign key relationship will link the tables. Indexes will be applied to frequently queried columns (like `telegram_user_id`, `mime_type`, etc.) to ensure good performance.

### Development Best Practices (Approved)

1.  **SQL Schema File:** A `schema.sql` file will be created containing `CREATE TABLE` queries for `files` and `users` tables to ensure consistent database setup.
2.  **Environment Configuration:** Database credentials and other sensitive configurations will be managed using environment variables (e.g., via a `.env` file and `phpdotenv` library) to support different environments (development, testing, production).
3.  **Data Type Optimization:** Data types will be reviewed and optimized (e.g., `BIGINT UNSIGNED` for IDs, realistic `VARCHAR` lengths) to improve database performance and storage efficiency.
4.  **Soft Deletes:** The `files` table will include a `deleted_at` column for soft deletion, allowing for data recovery and historical tracking without permanent removal.
5.  **Query Builder/ORM:** CodeIgniter's built-in Query Builder will be utilized for all database interactions to enhance security (preventing SQL injection), readability, and maintainability of the code.

### System Reliability and Performance (Approved)

1.  **Scheduled Tasks (Cron Jobs):** The system will use cron jobs to perform periodic tasks, including cleaning up old soft-deleted records and generating analytical reports.
2.  **Advanced Error Handling:** For critical errors, the system will send notifications to an admin via the Telegram bot. A retry-queue mechanism will be implemented for transient webhook failures.
3.  **Caching Strategy:** Both database query caching and full-page caching will be implemented using CodeIgniter's native capabilities to reduce database load and improve page load times.