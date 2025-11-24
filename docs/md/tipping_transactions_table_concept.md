# Tipping Transactions Table (`tipping_transactions`)

This table records every tipping event that occurs on the platform.

## Columns

| Column Name                     | Data Type     | Constraints              | Description                                                                                             |
| ------------------------------- | ------------- | ------------------------ | ------------------------------------------------------------------------------------------------------- |
| `id`                            | `BIGINT`      | `PRIMARY KEY`, `AUTO_INCREMENT` | Unique identifier for the tipping transaction.                                                          |
| `tipper_user_id`                | `BIGINT`      | `NOT NULL`, `FK to users.id` | The ID of the user who is giving the tip.                                                               |
| `recipient_user_id`             | `BIGINT`      | `NOT NULL`, `FK to users.id` | The ID of the user who is receiving the tip (the content creator).                                      |
| `folder_id`                     | `BIGINT`      | `NULL`, `FK to folders.id`   | The ID of the folder for which the tip was given. Can be `NULL` if the tip is given on a user's profile. |
| `gross_amount`                  | `DECIMAL(10,2)` | `NOT NULL`                 | The total amount of credits transferred from the tipper's balance.                                      |
| `platform_fee`                  | `DECIMAL(10,2)` | `NOT NULL`, `DEFAULT 0.00` | The commission/fee taken by the platform from this transaction.                                         |
| `net_amount`                    | `DECIMAL(10,2)` | `NOT NULL`                 | The final amount credited to the recipient's balance (`gross_amount` - `platform_fee`).                 |
| `balance_transaction_id_tipper` | `BIGINT`      | `NOT NULL`, `FK to balance_transactions.id` | The ID of the corresponding debit transaction in the `balance_transactions` table for the tipper.    |
| `balance_transaction_id_recipient`| `BIGINT`      | `NOT NULL`, `FK to balance_transactions.id` | The ID of the corresponding credit transaction in the `balance_transactions` table for the recipient. |
| `created_at`                    | `TIMESTAMP`   | `DEFAULT CURRENT_TIMESTAMP`| The timestamp when the tip was made.                                                                    |

## Indexes

- A composite index should be created on `(tipper_user_id, created_at)` to quickly retrieve tips given by a user.
- A composite index should be created on `(recipient_user_id, created_at)` to quickly retrieve tips received by a user.
- An index should be created on `folder_id`.

## Relationships

- **Users:** `tipper_user_id` and `recipient_user_id` both link to the `users` table.
- **Folders:** `folder_id` links to the `folders` table.
- **Balance Transactions:** `balance_transaction_id_tipper` and `balance_transaction_id_recipient` link to the `balance_transactions` table to provide a full audit trail of credit movement.

This structure ensures that every tip is recorded, traceable, and linked to the corresponding financial movements in the system.
