# Notifications Concept

To provide users with custom alerts, a dedicated system for managing notification rules will be implemented.

## Database Table: `notification_rules`

This table will store the rules created by users.

| Column Name      | Data Type      | Description                                                  | Notes                                      |
|------------------|----------------|--------------------------------------------------------------|--------------------------------------------|
| `id`             | `INT UNSIGNED` | Primary key for the rule.                                    | Auto-incrementing.                         |
| `user_id`        | `BIGINT UNSIGNED` | The ID of the user who owns this rule.                       | Foreign key to `users.id`. Indexed.        |
| `rule_name`      | `VARCHAR(255)` | A user-friendly name for the notification rule.              | e.g., "Urgent Work Files"                  |
| `trigger_type`   | `VARCHAR(50)`  | The type of event that triggers the notification.            | e.g., 'file_tag_match', 'new_comment', 'achievement_unlocked', 'system_announcement'. NOT NULL. Indexed. |
| `trigger_config_json` | `JSON`      | JSON configuration for the trigger (e.g., `{"tag": "urgent"}` for 'file_tag_match', `{"achievement_id": 5}` for 'achievement_unlocked'). | Nullable.                                  |
| `is_active`      | `BOOLEAN`      | Flag to enable or disable the rule.                          | NOT NULL, DEFAULT 1.                       |
| `template_id`    | `INT UNSIGNED` | ID of the notification template to use.                      | Foreign key to `notification_templates.id`. NOT NULL. Indexed. |
| `throttle_id`    | `BIGINT UNSIGNED` | ID of the notification throttle setting for this rule.       | Foreign key to `notification_throttles.id`. Nullable. Indexed. |
| `created_at`     | `DATETIME`     | Timestamp when the rule was created.                         | Defaults to `CURRENT_TIMESTAMP`.           |

## System Logic

1.  **Rule Management (Web UI):** Users will have a section in the web interface to create, view, edit, and delete their notification rules.
2.  **Triggering Logic:** The system will evaluate active notification rules based on their `trigger_type` and `trigger_config_json`.
    *   For `file_tag_match` triggers: When a new file is processed by the webhook (`/api/upload`), after saving the metadata, the system will check if any active notification rules with `trigger_type = 'file_tag_match'` match the tags of the new file based on `trigger_config_json`.
    *   For other trigger types (e.g., `new_comment`, `achievement_unlocked`), relevant system events will check for matching rules.
3.  **Sending Notification:** If a match is found and throttling allows, the system will trigger the bot to send a custom message to the user who owns the rule, using the specified `template_id` and substituting variables based on the event context.

## Schema Comments

All tables and columns in `schema.sql` will include descriptive comments for better documentation and understanding.
