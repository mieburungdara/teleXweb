# Users Table Concept (MySQL)

To make the application more user-friendly and the data more organized, a separate `users` table will be created. This table stores information about the Telegram users who interact with the bot.

**Table: `users`**

| Column Name  | Data Type    | Description                                                  | Notes                                      |
|--------------|--------------|--------------------------------------------------------------|--------------------------------------------|
| `id`         | `BIGINT`     | The unique ID of the Telegram user (`telegram_user_id`).     | Primary Key. Not auto-incrementing. Consider `BIGINT UNSIGNED`. |
| `first_name` | `VARCHAR(255)` | The user's first name, as provided by Telegram.              | Nullable. Review realistic max length for `VARCHAR`. |
| `last_name`  | `VARCHAR(255)` | The user's last name, as provided by Telegram.               | Nullable. Review realistic max length for `VARCHAR`. |
| `username`   | `VARCHAR(255)` | The user's Telegram username (@username).                    | Nullable, Indexed for potential lookups. Review realistic max length for `VARCHAR`. |
| `codename`   | `VARCHAR(50)`  | A unique, privacy-preserving name for the user.              | Nullable, Unique, Indexed. Generated or user-defined. |
| `role`       | `VARCHAR(50)`  | The user's role in the system (e.g., 'admin', 'editor', 'viewer'). | NOT NULL, DEFAULT 'viewer'. Indexed. |
| `status`     | `ENUM('active','blocked','deleted')` | The current status of the user.                              | NOT NULL, DEFAULT 'active'. Indexed. |
| `language_code`   | `VARCHAR(10)`  | The user's preferred language code (e.g., 'en', 'id').       | NOT NULL, DEFAULT 'en'.             |
| `has_completed_onboarding` | `BOOLEAN` | Flag to check if the user has seen the tutorial.             | NOT NULL, DEFAULT 0.                |
| `membership_level` | `VARCHAR(50)`  | The user''s membership level (e.g., ''free'', ''premium''). | NOT NULL, DEFAULT ''free''.         |
| `subscription_ends_at` | `DATETIME`   | Date when the user''s subscription ends.                     | Nullable.                           |
| `user_level`       | `INT UNSIGNED` | The current level of the user.                               | NOT NULL, DEFAULT 1.                |
| `achievement_points` | `INT UNSIGNED` | Points accumulated by the user for achievements.             | NOT NULL, DEFAULT 0.                |
| `timezone`   | `VARCHAR(64)`  | The user''s preferred timezone (e.g., ''Asia/Jakarta'').     | NOT NULL, DEFAULT ''UTC''.          |
| `last_activity_at` | `DATETIME`   | Timestamp of the user''s last interaction with the bot/web.  | Nullable.                           |
| `created_at` | `DATETIME`   | Timestamp when the user first interacted with the bot.       | Defaults to `CURRENT_TIMESTAMP`.           |
| `updated_at` | `DATETIME`   | Timestamp of the user's last interaction.                    | Updates on every new interaction.          |

### Schema Comments

All tables and columns in `schema.sql` will include descriptive comments for better documentation and understanding.

### Relationship

*   The `users.id` column serves as the primary key.
*   The `files.telegram_user_id` column will be a foreign key that references `users.id`.
*   A new `folders` table will be created, where `folders.user_id` will be a foreign key referencing `users.id`.
*   This allows for `JOIN` operations to retrieve user details along with file metadata, enabling the web interface to display user names instead of just IDs.
