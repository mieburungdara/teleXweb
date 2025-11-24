# Public Collection Folders Table Concept (MySQL)

This is a junction table to establish a many-to-many relationship between `public_collections` and `folders`. It defines which folders belong to which public collections.

## Table: `public_collection_folders`

This table links folders to public collections.

| Column Name          | Data Type         | Description                                                  | Notes                                      |
|----------------------|-------------------|--------------------------------------------------------------|--------------------------------------------|
| `id`                 | `BIGINT UNSIGNED` | Primary key for the junction entry.                          | Auto-incrementing.                         |
| `public_collection_id` | `BIGINT UNSIGNED` | ID of the public collection.                                 | Foreign key to `public_collections.id`. NOT NULL. Indexed. |
| `folder_id`          | `BIGINT UNSIGNED` | ID of the folder added to the collection.                    | Foreign key to `folders.id`. NOT NULL. Indexed. |
| `added_at`           | `DATETIME`        | Timestamp when the folder was added to this collection.      | Defaults to `CURRENT_TIMESTAMP`.           |

### Relationships

*   `public_collection_folders.public_collection_id` references `public_collections.id`.
*   `public_collection_folders.folder_id` references `folders.id`.

### Constraints

*   A unique constraint should be added on `(public_collection_id, folder_id)` to prevent the same folder from being added to the same collection multiple times.
