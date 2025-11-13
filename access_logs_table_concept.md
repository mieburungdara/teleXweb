# Access Logs Table Concept (MySQL)

To implement the "Trending This Week" feature, a new `access_logs` table will be introduced to track when files and folders are accessed. This will allow for aggregation and identification of popular content.

## Table: `access_logs`

This table stores a record of each time a file or folder is accessed.

| Column Name    | Data Type         | Description                                                  | Notes                                      |
|----------------|-------------------|--------------------------------------------------------------|--------------------------------------------|
| `id`           | `BIGINT UNSIGNED` | Primary key for the access log entry.                        | Auto-incrementing.                         |
| `user_id`      | `BIGINT UNSIGNED` | ID of the user who accessed the entity.                      | Foreign key to `users.id`. Nullable (for public access). Indexed. |
| `entity_type`  | `ENUM('file','folder')` | Type of entity that was accessed.                            | NOT NULL. Indexed.                         |
| `entity_id`    | `BIGINT UNSIGNED` | ID of the entity that was accessed.                          | NOT NULL. Indexed.                         |
| `accessed_at`  | `DATETIME`        | Timestamp when the entity was accessed.                      | Defaults to `CURRENT_TIMESTAMP`. Indexed.  |

### Relationships

*   `access_logs.user_id` references `users.id`.
*   `access_logs.entity_id` (when `entity_type` is 'file') references `files.id`.
*   `access_logs.entity_id` (when `entity_type` is 'folder') references `folders.id`.
