# Fitur: Sistem Pencapaian (Achievement System)

Dokumen ini menjelaskan arsitektur dan alur kerja dari Sistem Pencapaian yang telah diimplementasikan dalam proyek.

## 1. Tujuan Fitur

Sistem Pencapaian dirancang untuk memberikan penghargaan (rewards) berupa badge/lencana kepada pengguna yang berhasil memenuhi kriteria tertentu. Tujuannya adalah untuk meningkatkan keterlibatan (engagement) pengguna, memberikan rasa progresi, dan mendorong aktivitas positif di platform.

## 2. Komponen Utama

Fitur ini terdiri dari beberapa komponen utama yang bekerja bersama:

### a. Tabel Database

1.  **`achievements`**:
    *   **Tujuan:** Tabel ini adalah "kamus" yang mendefinisikan setiap pencapaian yang ada di platform.
    *   **Kolom Kunci:** `name`, `description`, `badge_icon_url`, dan `criteria_json`. Kolom `criteria_json` sangat penting karena mendefinisikan syarat untuk mendapatkan badge secara dinamis (contoh: `{"type": "total_income", "value": 1000}`).

2.  **`user_achievements`**:
    *   **Tujuan:** Ini adalah tabel penghubung (junction table) yang mencatat pencapaian mana yang telah diperoleh oleh pengguna mana, dan kapan diperolehnya.
    *   **Batasan:** Terdapat *unique constraint* pada `(user_id, achievement_id)` untuk memastikan seorang pengguna tidak bisa mendapatkan badge yang sama lebih dari satu kali.

### b. Kode Backend

1.  **Model:**
    *   `Achievement_model.php`: Mengelola data dari tabel `achievements`. Memiliki fungsi untuk mengambil definisi pencapaian berdasarkan tipe kriterianya.
    *   `User_achievement_model.php`: Mengelola data dari tabel `user_achievements`, seperti memberikan badge ke pengguna dan memeriksa badge apa saja yang sudah dimiliki.
    *   `Balance_Transaction_model.php`: Diperbarui dengan metode `get_total_income_for_user()` untuk menghitung metrik yang dibutuhkan oleh service.

2.  **Library/Service (`Achievement_service.php`):**
    *   Ini adalah "mesin" dari sistem. Isinya adalah logika bisnis untuk memeriksa apakah seorang pengguna layak mendapatkan sebuah badge.
    *   Metode utamanya, `check_all_achievements_for_user()`, akan memanggil metode-metode pengecekan yang lebih spesifik seperti `check_income_achievements()`.

3.  **Controller Cron Job (`Cron.php`):**
    *   Controller ini bukan untuk diakses oleh pengguna biasa. Tujuannya adalah untuk dipanggil secara otomatis oleh server.
    *   Metode `award_achievements()` akan mengambil daftar semua pengguna dan memanggil `Achievement_service` untuk setiap pengguna, guna memproses dan memberikan badge yang layak mereka dapatkan.

### c. Tampilan Frontend

1.  **Controller `Users.php`:** Metode `index()` (yang menampilkan profil) telah diperbarui untuk mengambil daftar pencapaian yang dimiliki oleh seorang pengguna.
2.  **View `user/profile.php`:** Telah diperbarui untuk menampilkan bagian "Achievements", di mana semua badge yang telah diperoleh pengguna akan ditampilkan.

## 3. Alur Kerja Pemberian Badge

1.  **Definisi:** Seorang admin membuat definisi pencapaian baru di tabel `achievements` (misalnya, badge 'Adept Earner' dengan kriteria `{"type": "total_income", "value": 1000}`).
2.  **Eksekusi Otomatis:** Sebuah cron job di server diatur untuk memanggil URL `https://your-domain.com/index.php/cron/award_achievements` secara berkala (misalnya, setiap jam).
3.  **Proses:** `Cron.php` menerima panggilan tersebut dan mulai bekerja.
4.  **Iterasi:** Controller mengambil semua ID pengguna dan melakukan loop.
5.  **Pengecekan:** Untuk setiap pengguna, `Achievement_service` dipanggil.
6.  **Kalkulasi:** Service menghitung metrik yang diperlukan (misal, total pendapatan pengguna adalah `1050`).
7.  **Evaluasi:** Service membandingkan metrik (`1050`) dengan kriteria badge ('Adept Earner' butuh `1000`).
8.  **Pemberian:** Karena pengguna memenuhi syarat dan belum memiliki badge tersebut, `User_achievement_model->award_achievement()` dipanggil, dan sebuah baris baru ditambahkan ke tabel `user_achievements`.
9.  **Tampilan:** Saat pengguna tersebut membuka halaman profilnya, badge 'Adept Earner' akan muncul.

## 4. Cara Menambah Tipe Kriteria Baru

Sistem ini dirancang untuk dapat dikembangkan. Untuk menambahkan tipe kriteria baru (misalnya, berdasarkan jumlah folder yang terjual):
1.  Buat metode baru di `Achievement_service.php`, contohnya `check_folders_sold_achievements()`.
2.  Tambahkan logika di dalamnya untuk menghitung jumlah folder yang telah dijual oleh seorang pengguna (kemungkinan memerlukan fungsi baru di `Folder_Purchase_model`).
3.  Panggil metode baru ini dari dalam `check_all_achievements_for_user()`.
4.  Masukkan definisi badge baru ke tabel `achievements` dengan JSON yang sesuai, misalnya: `{"type": "folders_sold", "value": 50}`.
