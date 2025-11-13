# Folders Table Concept (MySQL)

To allow users to organize their file metadata, a new `folders` table will be introduced.

**Table: `folders`**

| Column Name  | Data Type      | Description                                                  | Notes                                      |
|--------------|----------------|--------------------------------------------------------------|--------------------------------------------|
| `id`         | `INT UNSIGNED` | Primary key for the folder.                                  | Auto-incrementing.                         |
| `user_id`    | `BIGINT UNSIGNED` | The unique ID of the user who owns this folder.              | Foreign key to `users.id`. Indexed.        |
| `parent_folder_id` | `INT UNSIGNED` | ID of the parent folder, if this is a subfolder.             | Nullable. Foreign key to `folders.id`. Indexed. |
| `code`       | `VARCHAR(12)`  | A unique, short code for shareable links.                    | NOT NULL. UNIQUE. Indexed.                 |
| `folder_name`| `VARCHAR(255)` | The name of the folder.                                      | NOT NULL. Must be unique per `user_id`.    |
| `description`| `TEXT`         | Optional description for the folder.                         | Nullable.                                  |
| `tags`       | `VARCHAR(255)` | Comma-separated list of user-defined tags for categorization. | Nullable. Indexed. Consistent naming rules apply. |
| `folder_size`| `BIGINT UNSIGNED` | Aggregated size of all files (metadata) within the folder in bytes. | NOT NULL, DEFAULT 0. Updated on file changes. |
| `is_favorited` | `BOOLEAN`      | Flag to mark a folder as a favorite/pinned for quick access. | NOT NULL, DEFAULT 0. Indexed.       |
| `created_at` | `DATETIME`     | Timestamp when the folder was created.                       | Defaults to `CURRENT_TIMESTAMP`.           |
| `updated_at` | `DATETIME`     | Timestamp when the folder was last updated.                  | Defaults to `CURRENT_TIMESTAMP` ON UPDATE CURRENT_TIMESTAMP. |
| `deleted_at` | `DATETIME`     | Timestamp for soft-deletion of the folder.                   | Nullable.                                  |

### Relationship

*   The `folders.user_id` column is a foreign key that references `users.id`.
*   The `files.folder_id` column (newly added to the `files` table) is a foreign key that references `folders.id`.
*   This establishes a one-to-many relationship between `users` and `folders`, and between `folders` and `files`.
