# Achievements Table Concept (MySQL)

To implement the gamification system, an `achievements` table will be introduced to define the various badges and achievements users can earn.

## Table: `achievements`

This table stores the definitions of all available achievements.

| Column Name       | Data Type         | Description                                                  | Notes                                      |
|-------------------|-------------------|--------------------------------------------------------------|--------------------------------------------|
| `id`              | `INT UNSIGNED`    | Primary key for the achievement.                             | Auto-incrementing.                         |
| `name`            | `VARCHAR(255)`    | Name of the achievement (e.g., "File Organizer").            | NOT NULL. UNIQUE.                          |
| `description`     | `TEXT`            | A detailed description of the achievement.                   | NOT NULL.                                  |
| `criteria_json`   | `JSON`            | JSON object defining the criteria to earn this achievement (e.g., `{"type": "file_count", "value": 100}`). | NOT NULL.                                  |
| `badge_icon_url`  | `VARCHAR(255)`    | URL to the badge icon image.                                 | Nullable.                                  |
| `xp_reward`       | `INT UNSIGNED`    | Experience points awarded for earning this achievement.      | NOT NULL, DEFAULT 0.                       |
| `created_at`      | `DATETIME`        | Timestamp when the achievement definition was created.       | Defaults to `CURRENT_TIMESTAMP`.           |

### Relationships

*   No direct foreign key relationships from this table, but `user_achievements.achievement_id` will reference `achievements.id`.
