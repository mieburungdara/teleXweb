# Notifications Concept

To provide users with custom alerts, a dedicated system for managing notification rules will be implemented.

## Database Table: `notification_rules`

This table will store the rules created by users.

| Column Name      | Data Type      | Description                                                  | Notes                                      |
|------------------|----------------|--------------------------------------------------------------|--------------------------------------------|
| `id`             | `INT UNSIGNED` | Primary key for the rule.                                    | Auto-incrementing.                         |
| `user_id`        | `BIGINT UNSIGNED` | The ID of the user who owns this rule.                       | Foreign key to `users.id`. Indexed.        |
| `rule_name`      | `VARCHAR(255)` | A user-friendly name for the notification rule.              | e.g., "Urgent Work Files"                  |
| `trigger_tag`    | `VARCHAR(255)` | The tag that will trigger the notification.                  | e.g., "urgent"                             |
| `is_active`      | `BOOLEAN`      | Flag to enable or disable the rule.                          | NOT NULL, DEFAULT 1.                       |
| `created_at`     | `DATETIME`     | Timestamp when the rule was created.                         | Defaults to `CURRENT_TIMESTAMP`.           |

## System Logic

1.  **Rule Management (Web UI):** Users will have a section in the web interface to create, view, edit, and delete their notification rules.
2.  **Triggering Logic:** When a new file is processed by the webhook (`/api/upload`), after saving the metadata, the system will check if any active notification rules match the tags of the new file.
3.  **Sending Notification:** If a match is found, the system will trigger the bot to send a custom message to the user who owns the rule (e.g., "A new file with the tag '#urgent' has been added.").
