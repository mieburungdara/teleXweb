# Folder Comments Table Concept (MySQL)

To enable discussion and collaboration on shared folders, a new `folder_comments` table will be introduced. This table will support threaded comments.

## Table: `folder_comments`

This table stores comments made on shared folders.

| Column Name       | Data Type         | Description                                                  | Notes                                      |
|-------------------|-------------------|--------------------------------------------------------------|--------------------------------------------|
| `id`              | `BIGINT UNSIGNED` | Primary key for the comment.                                 | Auto-incrementing.                         |
| `folder_id`       | `BIGINT UNSIGNED` | ID of the folder the comment belongs to.                     | Foreign key to `folders.id`. NOT NULL. Indexed. |
| `user_id`         | `BIGINT UNSIGNED` | ID of the user who made the comment.                         | Foreign key to `users.id`. NOT NULL. Indexed. |
| `parent_comment_id` | `BIGINT UNSIGNED` | ID of the parent comment, for threaded replies.              | Foreign key to `folder_comments.id`. Nullable. Indexed. |
| `comment_text`    | `TEXT`            | The content of the comment.                                  | NOT NULL.                                  |
| `created_at`      | `DATETIME`        | Timestamp when the comment was created.                      | Defaults to `CURRENT_TIMESTAMP`.           |
| `updated_at`      | `DATETIME`        | Timestamp when the comment was last updated.                 | Defaults to `CURRENT_TIMESTAMP` ON UPDATE CURRENT_TIMESTAMP. |
| `deleted_at`      | `DATETIME`        | Timestamp if the comment was soft-deleted.                   | Nullable.                                  |

### Relationships

*   `folder_comments.folder_id` references `folders.id`.
*   `folder_comments.user_id` references `users.id`.
*   `folder_comments.parent_comment_id` references `folder_comments.id` (self-referencing for threading).
