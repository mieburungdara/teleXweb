# Balance Transactions Table Concept (MySQL)

To maintain an audit trail of all user balance changes, a new `balance_transactions` table will be introduced. This table will record every top-up, deduction, or other balance-affecting event.

## Table: `balance_transactions`

This table tracks all changes to user balances.

| Column Name         | Data Type         | Description                                                  | Notes                                      |
|---------------------|-------------------|--------------------------------------------------------------|--------------------------------------------|
| `id`                | `BIGINT UNSIGNED` | Primary key for the transaction record.                      | Auto-incrementing.                         |
| `user_id`           | `BIGINT UNSIGNED` | ID of the user whose balance was affected.                   | Foreign key to `users.id`. NOT NULL. Indexed. |
| `transaction_type`  | `ENUM('top_up','deduction','purchase','refund')` | Type of balance transaction.                 | NOT NULL.                                  |
| `amount`            | `DECIMAL(10,2)`   | The amount of balance change. Positive for top-up/refund, negative for deduction/purchase. | NOT NULL.                                  |
| `description`       | `TEXT`            | A brief description of the transaction (e.g., "Manual top-up by Admin X", "Purchase of 100 metadata entries"). | NOT NULL.                                  |
| `admin_id`          | `BIGINT UNSIGNED` | ID of the admin who initiated a manual transaction (e.g., top-up verification). | Nullable. Foreign key to `users.id`. Indexed. |
| `related_entity_type` | `VARCHAR(50)`   | Type of entity related to the transaction (e.g., 'subscription', 'feature_purchase'). | Nullable.                                  |
| `related_entity_id` | `BIGINT UNSIGNED` | ID of the entity related to the transaction.                 | Nullable.                                  |
| `created_at`        | `DATETIME`        | Timestamp when the transaction occurred.                     | Defaults to `CURRENT_TIMESTAMP`.           |

### Relationships

*   `balance_transactions.user_id` references `users.id`.
*   `balance_transactions.admin_id` references `users.id` (for admin-initiated transactions).
