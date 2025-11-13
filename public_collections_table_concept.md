# Public Collections Table Concept (MySQL)

To allow administrators to curate and highlight interesting folders, a new `public_collections` table will be introduced. These collections will be visible to all users and serve as a discovery mechanism.

## Table: `public_collections`

This table stores definitions for curated public collections of folders.

| Column Name       | Data Type         | Description                                                  | Notes                                      |
|-------------------|-------------------|--------------------------------------------------------------|--------------------------------------------|
| `id`              | `BIGINT UNSIGNED` | Primary key for the public collection.                       | Auto-incrementing.                         |
| `admin_id`        | `BIGINT UNSIGNED` | ID of the admin user who created this collection.            | Foreign key to `users.id`. NOT NULL. Indexed. |
| `collection_name` | `VARCHAR(255)`    | Name of the public collection.                               | NOT NULL. UNIQUE.                          |
| `description`     | `TEXT`            | A brief description of the collection.                       | Nullable.                                  |
| `created_at`      | `DATETIME`        | Timestamp when the collection was created.                   | Defaults to `CURRENT_TIMESTAMP`.           |
| `updated_at`      | `DATETIME`        | Timestamp when the collection was last updated.              | Defaults to `CURRENT_TIMESTAMP` ON UPDATE CURRENT_TIMESTAMP. |

### Relationships

*   `public_collections.admin_id` references `users.id`.
