# Database Concept (MySQL)

A single table will be used to store metadata for each file received.

**Table: `files`**

| Column Name          | Data Type    | Description                                                  | Notes                               |
| -------------------- | ------------ | ------------------------------------------------------------ | ----------------------------------- |
| `id`                 | `INT`        | Primary key for the record.                                  | Auto-incrementing.                  |
| `telegram_user_id`   | `BIGINT`     | The unique ID of the Telegram user who sent the file.        | Indexed for quick lookups.          |
| `telegram_file_id`   | `VARCHAR(255)` | The unique file ID provided by Telegram.                     | Useful for potential future interactions with Telegram API. |
| `file_name`          | `VARCHAR(255)` | The new, sanitized name of the file stored on the server.    | e.g., `timestamp_randomstring.ext`  |
| `original_file_name` | `VARCHAR(255)` | The original name of the file as sent by the user.           |                                     |
| `file_size`          | `INT`        | The size of the file in bytes.                               |                                     |
| `mime_type`          | `VARCHAR(100)` | The MIME type of the file.                                   | e.g., `image/jpeg`, `application/pdf` |
| `created_at`         | `DATETIME`   | Timestamp when the record was created.                       | Defaults to `CURRENT_TIMESTAMP`.    |
