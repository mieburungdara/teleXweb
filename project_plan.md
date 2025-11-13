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
**Main Features:**
1.  **Penerimaan & Penyimpanan Metadata File:** Backend menerima metadata file dari bot. Bot akan menyalin file ke channel storage Telegram menggunakan `copyMessages` dan menyimpan `storage_channel_id`, `storage_message_id`, serta `media_group_id` (jika bagian dari album) di database.
2.  **Distribusi File via Channel Storage:** Memanfaatkan `storage_channel_id` dan `storage_message_id` untuk memungkinkan bot lain atau pengguna yang berwenang mengakses dan mendistribusikan file langsung dari channel storage Telegram.
3.  **Pelacakan Status Pemrosesan File:** Melacak siklus hidup metadata file (misalnya, 'pending', 'processed', 'indexed', 'failed') dengan indikator keandalan pengiriman *webhook*.
4.  **Perintah Interaktif Bot:** Bot mendukung berbagai perintah untuk interaksi pengguna (misalnya, `/start`, `/help`, `/search`, `/fav`).
5.  **Antarmuka Web yang Canggih:** Menyediakan berbagai mode tampilan (tabel, galeri dengan pengelompokan album, detail), pencarian lanjutan (berdasarkan tag folder), pengeditan *inline* (nama file), dan sistem favorit untuk mengelola file, dengan dukungan untuk sistem *tagging* terpusat.
6.  **Manajemen Folder Komprehensif:** Pengguna dapat membuat, mengelola, dan mengelompokkan file metadata mereka ke dalam folder, termasuk:
    *   **Folder Bersarang:** Mendukung hierarki subfolder untuk organisasi yang lebih mendalam.
    *   **Pemindahan File/Folder:** Memindahkan file dan folder ke lokasi tujuan yang dipilih.
    *   **Tagging Folder:** Menambahkan tag pada folder menggunakan sistem *tagging* terpusat.
    *   **Ukuran Folder:** Ukuran folder dilacak dan ditampilkan.
    *   **Quick Actions (Pin/Favorite):** Menandai folder sebagai favorit atau 'disematkan' untuk akses cepat.
    *   **Rating & Review Folder:** Memberikan *rating* (1-5 bintang) dan *review* teks pada folder.
    *   **Berbagi Folder:** Membuat kode unik untuk folder, memungkinkan berbagi sebagai tautan dengan *Telegram Deep Links*.
    *   **Folder Stars/Hearts:** Pengguna dapat "menyukai" atau "memfavoritkan" folder publik yang dibagikan oleh pengguna lain.
7.  **Smart Collections:** Pengguna dapat membuat koleksi file yang dibuat secara otomatis berdasarkan kriteria yang ditentukan (misalnya, tanggal, tag folder, tipe file).
8.  **Tampilan & Navigasi UI Lanjutan:**
    *   **File Timeline:** Tampilan linimasa visual riwayat unggahan file.
    *   **Quick Preview Modal:** Melihat detail metadata file dalam modal tanpa meninggalkan halaman.
    *   **Breadcrumb Navigation:** Menampilkan hierarki folder saat menjelajahi.
    *   **Folder Stats Widget:** Widget sidebar yang menampilkan statistik agregat folder.
    *   **Trending This Week:** Bagian khusus yang menampilkan file dan folder yang paling banyak diakses selama seminggu terakhir.
9.  **Manajemen & Analitik (Admin):** Dasbor admin untuk mengelola pengguna (termasuk menggunakan `codename` untuk privasi), menetapkan peran, melihat analitik sistem, memantau kesehatan sistem (System Health Dashboard), meninjau riwayat tindakan admin (Audit Trail), mengelola upaya *webhook* yang gagal (Webhook Retry Dashboard), mendeteksi serta mengkonsolidasikan tag duplikat, dan mengkurasi koleksi publik (Public Collections).
10. **Notifikasi Kustom:** Pengguna dapat membuat aturan untuk menerima notifikasi di Telegram berdasarkan berbagai pemicu (misalnya, pencocokan tag file, komentar baru, pembukaan pencapaian, pengumuman sistem). Ini termasuk **Pembatasan Notifikasi** untuk mencegah *spam* dan **Template Notifikasi** untuk pesan yang dapat disesuaikan.
    *   **UI untuk Jenis Pemicu Baru:** Desain dan implementasikan UI web untuk pengguna membuat dan mengelola aturan notifikasi untuk jenis pemicu baru (komentar, pencapaian, pengumuman sistem) dengan formulir dinamis.
    *   **Penangan Notifikasi Khusus:** Buat fungsi atau kelas penangan khusus untuk setiap `trigger_type` yang mengekstrak data relevan, menyiapkan variabel templat, dan berinteraksi dengan model notifikasi.
    *   **Antarmuka Admin untuk Pengumuman Sistem:** Buat antarmuka admin untuk menyusun dan mengirim pengumuman di seluruh sistem, yang akan memicu notifikasi bagi pengguna yang berlangganan aturan `system_announcement`.
11. **Profil Pengguna:** Profil publik yang menampilkan koleksi yang dibagikan pengguna dan rata-rata *rating* folder.
12. **Komentar pada Folder yang Dibagikan:** Memungkinkan diskusi konten folder dengan kolaborator melalui komentar berulir.
13. **Personalisasi:** Dukungan untuk multi-bahasa (i18n) dengan strategi manajemen terjemahan yang terencana (menggunakan `language_code`), preferensi zona waktu, dan pelacakan aktivitas terakhir pengguna.
14. **Gamifikasi & Keanggotaan:** Implementasi sistem gamifikasi yang komprehensif, termasuk:
    *   **Badges/Achievements:** Pengguna dapat memperoleh berbagai *badge* atau pencapaian.
    *   **XP System:** Pengguna mendapatkan poin pengalaman (XP) untuk menyelesaikan tugas.
    *   **User Levels:** XP berkontribusi pada level pengguna.
    *   **Leaderboard:** Papan peringkat (opt-in) yang menampilkan pengguna teratas berdasarkan pencapaian/kontribusi.
    *   **Milestone Celebrations:** Notifikasi dan isyarat visual untuk merayakan pencapaian penting pengguna.
15. **Dokumentasi Pengguna:** Menyediakan panduan dan FAQ yang komprehensif.
16. **Monetisasi (Model Langganan Berjenjang):**
    *   **Model Freemium:** Tawarkan tingkat gratis dengan fungsionalitas dasar dan tingkat premium (misalnya, TeleX Pro, TeleX Enterprise) dengan fitur, batasan, dan dukungan yang berbeda.
    *   **Manajemen Langganan:** Sediakan antarmuka pengguna untuk mengelola langganan, termasuk peningkatan, penurunan, dan riwayat penagihan.
    *   **Integrasi Gateway Pembayaran:** Integrasikan dengan penyedia pembayaran (misalnya, Stripe) untuk menangani penagihan berulang dan siklus hidup langganan.
    *   **Pembatasan Fitur:** Terapkan logika untuk membatasi akses ke fitur premium berdasarkan paket langganan pengguna.
17. **Manajemen Saldo Pengguna:**
    *   **Saldo Pengguna:** Setiap pengguna akan memiliki saldo yang dapat diisi ulang secara manual oleh admin.
    *   **Proses Top-up Manual:** Pengguna menghubungi admin untuk meminta top-up saldo.
    *   **Verifikasi & Pembaruan Admin:** Admin memverifikasi pembayaran secara manual dan memperbarui saldo pengguna melalui antarmuka admin.
    *   **Log Transaksi Saldo:** Semua perubahan saldo akan dicatat dalam tabel `balance_transactions` untuk tujuan audit.



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
5.  **MySQL Database Setup Concept:** Create a MySQL database with two tables: `telegram_files` to store file metadata and a `users` table to store user information, including a `status` ENUM for managing user states (active, blocked, deleted). A foreign key relationship will link the tables. Indexes will be applied to frequently queried columns (like `telegram_user_id`, `mime_type`, etc.) to ensure good performance.

### Development Best Practices (Approved)

1.  **SQL Schema File:** A `schema.sql` file will be created containing `CREATE TABLE` queries for `files` and `users` tables to ensure consistent database setup. This file will include descriptive comments for all tables and columns.
2.  **Environment Configuration:** Database credentials and other sensitive configurations will be managed using environment variables (e.g., via a `.env` file and `phpdotenv` library) to support different environments (development, testing, production).
3.  **Data Type Optimization:** Data types will be reviewed and optimized (e.g., `BIGINT UNSIGNED` for IDs, realistic `VARCHAR` lengths) to improve database performance and storage efficiency.
4.  **Soft Deletes:** The `files` table will include a `deleted_at` column for soft deletion, allowing for data recovery and historical tracking without permanent removal.
5.  **Query Builder/ORM:** CodeIgniter's built-in Query Builder will be utilized for all database interactions to enhance security (preventing SQL injection), readability, and maintainability of the code.

### System Reliability and Performance (Approved)

1.  **Scheduled Tasks (Cron Jobs):** The system will use cron jobs to perform periodic tasks, including cleaning up old soft-deleted records and generating analytical reports.
2.  **Advanced Error Handling:** For critical errors, the system will send notifications to an admin via the Telegram bot. A retry-queue mechanism will be implemented for transient webhook failures.
3.  **Caching Strategy:** Both database query caching and full-page caching will be implemented using CodeIgniter's native capabilities to reduce database load and improve page load times.