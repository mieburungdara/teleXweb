# Failed Webhooks Table Concept (MySQL)

To provide administrators with visibility and control over failed webhook attempts, a new `failed_webhooks` table will be introduced. This table will store details of webhook requests that could not be processed successfully, allowing for manual review and retry.

## Table: `failed_webhooks`

This table stores information about webhook attempts that failed processing.

| Column Name          | Data Type                               | Description                                                  | Notes                                      |
|----------------------|-----------------------------------------|--------------------------------------------------------------|--------------------------------------------|
| `id`                 | `BIGINT UNSIGNED`                       | Primary key for the failed webhook entry.                    | Auto-incrementing.                         |
| `webhook_payload_json` | `JSON`                                | The complete JSON payload of the failed webhook.             | NOT NULL.                                  |
| `error_message`      | `TEXT`                                  | The error message that caused the failure.                   | Nullable.                                  |
| `attempt_count`      | `TINYINT UNSIGNED`                      | Number of retry attempts made so far.                        | NOT NULL, DEFAULT 0.                       |
| `last_attempt_at`    | `DATETIME`                              | Timestamp of the last retry attempt.                         | Nullable.                                  |
| `status`             | `ENUM('pending','retried','failed_permanently')` | Current status of the failed webhook.                        | NOT NULL, DEFAULT 'pending'. Indexed.      |
| `created_at`         | `DATETIME`                              | Timestamp when the webhook was first recorded as failed.     | Defaults to `CURRENT_TIMESTAMP`.           |
| `updated_at`         | `DATETIME`                              | Timestamp when the entry was last updated (e.g., after a retry). | Defaults to `CURRENT_TIMESTAMP` ON UPDATE CURRENT_TIMESTAMP. |

### Relationships

*   No direct foreign key relationships to other tables, as it stores raw webhook data. However, the `webhook_payload_json` might contain `user_id` or `file_id` that could be used for contextual linking in the application logic.
