# User Achievements Table Concept (MySQL)

This junction table tracks which achievements each user has earned.

## Table: `user_achievements`

This table links users to the achievements they have earned.

| Column Name       | Data Type         | Description                                                  | Notes                                      |
|-------------------|-------------------|--------------------------------------------------------------|--------------------------------------------|
| `id`              | `BIGINT UNSIGNED` | Primary key for the user achievement entry.                  | Auto-incrementing.                         |
| `user_id`         | `BIGINT UNSIGNED` | ID of the user who earned the achievement.                   | Foreign key to `users.id`. NOT NULL. Indexed. |
| `achievement_id`  | `INT UNSIGNED`    | ID of the achievement earned.                                | Foreign key to `achievements.id`. NOT NULL. Indexed. |
| `achieved_at`     | `DATETIME`        | Timestamp when the achievement was earned.                   | Defaults to `CURRENT_TIMESTAMP`.           |

### Relationships

*   `user_achievements.user_id` references `users.id`.
*   `user_achievements.achievement_id` references `achievements.id`.

### Constraints

*   A unique constraint should be added on `(user_id, achievement_id)` to prevent a user from earning the same achievement multiple times.
