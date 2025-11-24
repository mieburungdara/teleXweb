# Folder Reviews Table Concept (MySQL)

To allow users to rate and review folders, a new `folder_reviews` table will be introduced.

## Table: `folder_reviews`

This table stores user ratings and descriptive reviews for folders.

| Column Name   | Data Type          | Description                                                  | Notes                                      |
|---------------|--------------------|--------------------------------------------------------------|--------------------------------------------|
| `id`          | `INT UNSIGNED`     | Primary key for the review.                                  | Auto-incrementing.                         |
| `folder_id`   | `INT UNSIGNED`     | ID of the folder being reviewed.                             | Foreign key to `folders.id`. Indexed.      |
| `user_id`     | `BIGINT UNSIGNED`  | ID of the user who submitted the review.                     | Foreign key to `users.id`. Indexed.        |
| `rating`      | `TINYINT UNSIGNED` | Star rating (1-5).                                           | NOT NULL. CHECK (rating BETWEEN 1 AND 5).  |
| `review_text` | `TEXT`             | Descriptive review text.                                     | Nullable.                                  |
| `created_at`  | `DATETIME`         | Timestamp when the review was created.                       | Defaults to `CURRENT_TIMESTAMP`.           |
| `updated_at`  | `DATETIME`         | Timestamp when the review was last updated.                  | Defaults to `CURRENT_TIMESTAMP` ON UPDATE CURRENT_TIMESTAMP. |
| `deleted_at`  | `DATETIME`         | Timestamp for soft-deletion of the review.                   | Nullable.                                  |

### Relationships

*   `folder_reviews.folder_id` references `folders.id`.
*   `folder_reviews.user_id` references `users.id`.
*   A user can submit only one review per folder (unique constraint on `folder_id`, `user_id`).
