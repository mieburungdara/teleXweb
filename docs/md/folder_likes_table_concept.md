# Folder Likes Table Concept (MySQL)

To implement the "Folder Stars/Hearts" feature, a new `folder_likes` table will be introduced. This table will track which users have "liked" or "favorited" publicly shared folders, allowing for social interaction without modifying the original folder.

## Table: `folder_likes`

This table stores a record of each time a user likes a folder.

| Column Name    | Data Type         | Description                                                  | Notes                                      |
|----------------|-------------------|--------------------------------------------------------------|--------------------------------------------|
| `id`           | `BIGINT UNSIGNED` | Primary key for the folder like entry.                       | Auto-incrementing.                         |
| `user_id`      | `BIGINT UNSIGNED` | ID of the user who liked the folder.                         | Foreign key to `users.id`. NOT NULL. Indexed. |
| `folder_id`    | `BIGINT UNSIGNED` | ID of the folder that was liked.                             | Foreign key to `folders.id`. NOT NULL. Indexed. |
| `created_at`   | `DATETIME`        | Timestamp when the like was given.                           | Defaults to `CURRENT_TIMESTAMP`.           |

### Relationships

*   `folder_likes.user_id` references `users.id`.
*   `folder_likes.folder_id` references `folders.id`.

### Constraints

*   A unique constraint should be added on `(user_id, folder_id)` to prevent a user from liking the same folder multiple times.
