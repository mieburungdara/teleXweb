# Tags Table Concept (MySQL)

To implement a shared, unique, and robust tagging system, a new `tags` table and two junction tables will be introduced.

## Table: `tags`

This table stores all unique tag names available in the system.

| Column Name        | Data Type         | Description                                                  | Notes                                      |
|--------------------|-------------------|--------------------------------------------------------------|--------------------------------------------|
| `id`               | `INT UNSIGNED`    | Primary key for the tag.                                     | Auto-incrementing.                         |
| `tag_name`         | `VARCHAR(50)`     | The unique name of the tag.                                  | NOT NULL. UNIQUE. Indexed.                 |
| `created_by_user_id` | `BIGINT UNSIGNED` | The ID of the user who first created this tag.               | Foreign key to `users.id`. Nullable (if system-created). |
| `created_at`       | `DATETIME`        | Timestamp when the tag was created.                          | Defaults to `CURRENT_TIMESTAMP`.           |

## Junction Table: `folder_tags`

This table manages the many-to-many relationship between `folders` and `tags`.

| Column Name  | Data Type      | Description                                                  | Notes                                      |
|--------------|----------------|--------------------------------------------------------------|--------------------------------------------|
| `folder_id`  | `INT UNSIGNED` | ID of the folder.                                            | Foreign key to `folders.id`.               |
| `tag_id`     | `INT UNSIGNED` | ID of the tag.                                               | Foreign key to `tags.id`.                  |
| `created_at` | `DATETIME`     | Timestamp when the tag was associated with the folder.       | Defaults to `CURRENT_TIMESTAMP`.           |
| (Primary Key)| (`folder_id`, `tag_id`) | Composite primary key to ensure uniqueness.                  |                                            |

### Relationships

*   `tags.created_by_user_id` references `users.id`.
*   `folder_tags.folder_id` references `folders.id`.
*   `folder_tags.tag_id` references `tags.id`.
