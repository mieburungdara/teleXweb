# XP Transactions Table Concept (MySQL)

To track the experience points (XP) gained by users for various tasks, an `xp_transactions` table will be introduced. This allows for a detailed audit of XP changes and supports the XP system and leaderboard.

## Table: `xp_transactions`

This table records every instance of a user gaining or losing XP.

| Column Name       | Data Type         | Description                                                  | Notes                                      |
|-------------------|-------------------|--------------------------------------------------------------|--------------------------------------------|
| `id`              | `BIGINT UNSIGNED` | Primary key for the XP transaction.                          | Auto-incrementing.                         |
| `user_id`         | `BIGINT UNSIGNED` | ID of the user involved in the transaction.                  | Foreign key to `users.id`. NOT NULL. Indexed. |
| `xp_amount`       | `INT`             | Amount of XP gained or lost (can be positive or negative).   | NOT NULL.                                  |
| `reason`          | `VARCHAR(255)`    | Description of why the XP was gained/lost (e.g., "File Upload", "Folder Shared", "Achievement Unlocked"). | NOT NULL.                                  |
| `entity_type`     | `VARCHAR(50)`     | Type of entity related to the XP transaction (e.g., 'file', 'folder', 'achievement'). | Nullable.                                  |
| `entity_id`       | `BIGINT UNSIGNED` | ID of the entity related to the XP transaction.              | Nullable.                                  |
| `created_at`      | `DATETIME`        | Timestamp when the XP transaction occurred.                  | Defaults to `CURRENT_TIMESTAMP`.           |

### Relationships

*   `xp_transactions.user_id` references `users.id`.
*   `xp_transactions.entity_id` could conditionally reference `files.id`, `folders.id`, or `achievements.id` based on `entity_type`.
