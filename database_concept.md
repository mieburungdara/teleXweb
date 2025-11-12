# Database Concept (MySQL)

A single table will be used to store metadata for each file received.

**Table: `files`**

| Column Name          | Data Type    | Description                                                  | Notes                               |
| -------------------- | ------------ | ------------------------------------------------------------ | ----------------------------------- |
| `id`                 | `INT`        | Primary key, auto-increment.                                 | Consider `INT UNSIGNED` for optimization. |
| `telegram_user_id`   | `BIGINT`     | The unique ID of the Telegram user who sent the file.        | Indexed for quick lookups. Consider `BIGINT UNSIGNED`. |
| `telegram_file_id`   | `VARCHAR(255)` | The unique file ID provided by Telegram.                     | Useful for potential future interactions with Telegram API. |
| `thumbnail_file_id`  | `VARCHAR(255)` | The `file_id` of the thumbnail for image previews.           | Nullable. Provided by Telegram for image files. |
| `file_name`          | `VARCHAR(255)` | The new, sanitized name of the file stored on the server.    | Review realistic max length for `VARCHAR`. |
| `original_file_name` | `VARCHAR(255)` | The original name of the file as sent by the user.           | Review realistic max length for `VARCHAR`. |
| `file_size`          | `INT`        | The size of the file in bytes.                               |                                     |
| `mime_type`          | `VARCHAR(100)` | The MIME type of the file.                                   | Indexed for filtering by type. Review realistic max length for `VARCHAR`. |
| `tags`               | `VARCHAR(255)` | User-defined tags for categorization.                        | Nullable. e.g., "work,report,q4". Review realistic max length for `VARCHAR`. |
| `is_favorited`       | `BOOLEAN`    | Flag to mark a file as a favorite.                           | NOT NULL, DEFAULT 0. Indexed for quick filtering. |
| `created_at`         | `DATETIME`   | Timestamp when the record was created.                       | Indexed for date-range queries.     |
| `deleted_at`         | `DATETIME`   | Timestamp when the record was soft-deleted.                  | Nullable. Used for soft deletes.    |

## Table Relationships

A second table, `users`, will be created to store user information.

*   The `files.telegram_user_id` column will act as a **foreign key**.
*   It will reference the `id` column in the `users` table (`users.id`).
*   This relationship allows for joining the two tables to fetch user details (like `first_name` and `username`) along with the file metadata.
*   Refer to `users_table_concept.md` for the full structure of the `users` table.

