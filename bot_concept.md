# Telegram Bot System Concept

The bot acts as the entry point for files into the system.

*   **Method: Webhook**
    *   A more efficient method than long polling. A single PHP script (`webhook.php`) will be created and its URL will be registered with the Telegram Bot API.
    *   **`webhook.php` Script:**
        1.  **Receive Update:** Telegram will send a POST request with a JSON payload to this script whenever a new message (or file) is sent to the bot.
        2.  **Validate:** The script will perform a basic check to ensure the request is from Telegram (e.g., by checking a secret token).
        3.  **Proses Metadata File:** Jika pembaruan berisi file, skrip akan:
            a.  Mengekstrak `file_id`, `file_name` (jika tersedia), `mime_type`, dan `file_size` dari payload JSON.
            b.  Tidak akan mengunduh file fisik.
        4.  **Teruskan ke CodeIgniter:** Skrip kemudian akan membuat permintaan cURL ke endpoint API CodeIgniter (`/api/upload`), mengirimkan metadata file yang diekstrak (Telegram user ID, `telegram_file_id`, original filename, MIME type, ukuran file, dll.).
        5.  **Respon ke Telegram:** Skrip mengirimkan respons `200 OK` ke Telegram untuk mengakui penerimaan pembaruan. Ini juga dapat secara opsional mengirim pesan konfirmasi kembali ke pengguna melalui API bot.
