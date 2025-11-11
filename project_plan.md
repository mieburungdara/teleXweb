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
1.  **Telegram Bot Integration:** A bot that users can send files to, forwarding them to the CodeIgniter backend.
2.  **Penerimaan & Penyimpanan Metadata File:** Backend CodeIgniter akan menerima metadata file dari bot, dan mencatatnya ke database MySQL. File fisik tidak akan diunduh atau disimpan di server.
3.  **File Listing Web Interface:** A web page built with Bootstrap 5 will display a list of all received files, complete with details, fetched from the CodeIgniter API.
4.  **Melihat Detail Metadata File:** Kemampuan untuk melihat detail metadata file yang diterima dari antarmuka web.

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
5.  **MySQL Database Setup Concept:** Create a MySQL database and a table (e.g., `telegram_files`) to store file metadata. This table will include columns like `id`, `telegram_user_id`, `telegram_file_id`, `file_name`, `original_file_name`, `mime_type`, `file_size`, and `upload_date`.