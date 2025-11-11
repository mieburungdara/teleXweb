# Feature Concept

1.  **File Reception via Telegram Bot:** Users send files to a dedicated Telegram bot.
2.  **Penyimpanan Metadata File:** Sistem menyimpan metadata file (seperti `telegram_file_id`, nama asli, tipe, ukuran, dll.) ke dalam database. File fisik tidak diunduh atau disimpan di server.
3.  **Web-Based File Listing:** A private, password-protected (future enhancement) web page lists all received files in a table.
4.  **File Details Display:** The table shows the original filename, file type, size, sender's Telegram ID, and the date it was received.
5.  **Melihat Detail Metadata File:** Setiap entri dalam tabel memiliki opsi untuk melihat detail metadata file.
