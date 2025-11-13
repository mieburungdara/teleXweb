# Notification Throttles Table Concept (MySQL)

To prevent notification spam and ensure a positive user experience, a new `notification_throttles` table will be introduced. This table will track notification sending activity per user and notification type, allowing for rate limiting.

## Table: `notification_throttles`

This table tracks notification sending activity for throttling purposes.

| Column Name         | Data Type         | Description                                                  | Notes                                      |
|---------------------|-------------------|--------------------------------------------------------------|--------------------------------------------|
| `id`                | `BIGINT UNSIGNED` | Primary key for the throttle entry.                          | Auto-incrementing.                         |
| `user_id`           | `BIGINT UNSIGNED` | ID of the user receiving notifications.                      | Foreign key to `users.id`. NOT NULL. Indexed. |
| `notification_type` | `VARCHAR(50)`     | Type of notification being throttled (e.g., 'tag_match', 'milestone_reached'). | NOT NULL. Indexed.                         |
| `last_sent_at`      | `DATETIME`        | Timestamp of the last successful notification send for this type and user. | Nullable.                                  |
| `send_count`        | `INT UNSIGNED`    | Number of notifications sent within the current throttling period. | NOT NULL, DEFAULT 0.                       |
| `reset_at`          | `DATETIME`        | Timestamp when the `send_count` should be reset.             | Nullable.                                  |
| `created_at`        | `DATETIME`        | Timestamp when the throttle entry was first created.         | Defaults to `CURRENT_TIMESTAMP`.           |
| `updated_at`        | `DATETIME`        | Timestamp when the entry was last updated.                   | Defaults to `CURRENT_TIMESTAMP` ON UPDATE CURRENT_TIMESTAMP. |

### Relationships

*   `notification_throttles.user_id` references `users.id`.

### Constraints

*   A unique constraint should be added on `(user_id, notification_type)` to ensure only one throttle entry per user per notification type.
