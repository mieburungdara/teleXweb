# Notification Templates Table Concept (MySQL)

To allow for customizable and flexible notification messages, a new `notification_templates` table will be introduced. This table will store predefined templates that can be used for various notification types, supporting variable substitution.

## Table: `notification_templates`

This table stores customizable notification message templates.

| Column Name        | Data Type         | Description                                                  | Notes                                      |
|--------------------|-------------------|--------------------------------------------------------------|--------------------------------------------|
| `id`               | `INT UNSIGNED`    | Primary key for the template.                                | Auto-incrementing.                         |
| `name`             | `VARCHAR(255)`    | A unique name for the template (e.g., 'File Upload Tag Match'). | NOT NULL. UNIQUE.                          |
| `template_content` | `TEXT`            | The message content with variable placeholders (e.g., "New file '{{file_name}}' with tag '{{tag_name}}' in folder '{{folder_name}}'!"). | NOT NULL.                                  |
| `variables_json`   | `JSON`            | JSON array of expected variables for substitution (e.g., `["file_name", "tag_name", "folder_name"]`). | Nullable.                                  |
| `created_at`       | `DATETIME`        | Timestamp when the template was created.                     | Defaults to `CURRENT_TIMESTAMP`.           |
| `updated_at`       | `DATETIME`        | Timestamp when the template was last updated.                | Defaults to `CURRENT_TIMESTAMP` ON UPDATE CURRENT_TIMESTAMP. |

### Relationships

*   No direct foreign key relationships from this table, but `notification_rules.template_id` will reference `notification_templates.id`.
