# Audit Logs Table Concept (MySQL)

To provide a complete history of admin actions and ensure accountability, a new `audit_logs` table will be introduced.

## Table: `audit_logs`

This table stores a detailed record of significant actions performed within the system, especially by admin users.

| Column Name    | Data Type         | Description                                                  | Notes                                      |
|----------------|-------------------|--------------------------------------------------------------|--------------------------------------------|
| `id`           | `BIGINT UNSIGNED` | Primary key for the audit log entry.                         | Auto-incrementing.                         |
| `user_id`      | `BIGINT UNSIGNED` | ID of the user who performed the action.                     | Foreign key to `users.id`. Nullable (for system actions). Indexed. |
| `action`       | `VARCHAR(50)`     | Type of action performed (e.g., 'create', 'update', 'delete', 'login', 'block_user', 'change_role'). | NOT NULL.                                  |
| `entity_type`  | `VARCHAR(50)`     | Type of entity affected (e.g., 'user', 'folder', 'file', 'tag', 'notification_rule'). | NOT NULL.                                  |
| `entity_id`    | `BIGINT UNSIGNED` | ID of the entity affected by the action.                     | Nullable (for actions not tied to a specific entity ID). Indexed. |
| `old_value_json` | `JSON`          | JSON representation of the entity's state *before* the action. | Nullable.                                  |
| `new_value_json` | `JSON`          | JSON representation of the entity's state *after* the action. | Nullable.                                  |
| `ip_address`   | `VARCHAR(45)`     | IP address from which the action was performed.              | Nullable. Supports IPv4 and IPv6.          |
| `created_at`   | `DATETIME`        | Timestamp when the action was performed.                     | Defaults to `CURRENT_TIMESTAMP`.           |

### Relationships

*   `audit_logs.user_id` references `users.id`.
