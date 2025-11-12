# Users Table Concept (MySQL)

To make the application more user-friendly and the data more organized, a separate `users` table will be created. This table stores information about the Telegram users who interact with the bot.

**Table: `users`**

| Column Name  | Data Type    | Description                                                  | Notes                                      |
|--------------|--------------|--------------------------------------------------------------|--------------------------------------------|
| `id`         | `BIGINT`     | The unique ID of the Telegram user (`telegram_user_id`).     | Primary Key. Not auto-incrementing. Consider `BIGINT UNSIGNED`. |
| `first_name` | `VARCHAR(255)` | The user's first name, as provided by Telegram.              | Nullable. Review realistic max length for `VARCHAR`. |
| `last_name`  | `VARCHAR(255)` | The user's last name, as provided by Telegram.               | Nullable. Review realistic max length for `VARCHAR`. |
| `username`   | `VARCHAR(255)` | The user's Telegram username (@username).                    | Nullable, Indexed for potential lookups. Review realistic max length for `VARCHAR`. |
| `created_at` | `DATETIME`   | Timestamp when the user first interacted with the bot.       | Defaults to `CURRENT_TIMESTAMP`.           |
| `updated_at` | `DATETIME`   | Timestamp of the user's last interaction.                    | Updates on every new interaction.          |

### Relationship

*   The `users.id` column serves as the primary key.
*   The `files.telegram_user_id` column will be a foreign key that references `users.id`.
*   This allows for `JOIN` operations to retrieve user details along with file metadata, enabling the web interface to display user names instead of just IDs.
