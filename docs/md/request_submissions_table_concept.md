# Request Submissions Table (`request_submissions`)

This table links a creator's submitted folder to a specific `request`. Each row represents one attempt by a creator to fulfill a request.

## Columns

| Column Name              | Data Type                                  | Constraints                            | Description                                                                                                    |
| ------------------------ | ------------------------------------------ | -------------------------------------- | -------------------------------------------------------------------------------------------------------------- |
| `id`                     | `BIGINT`                                   | `PRIMARY KEY`, `AUTO_INCREMENT`          | Unique identifier for the submission.                                                                          |
| `request_id`             | `BIGINT`                                   | `NOT NULL`, `FK to requests.id`          | The ID of the request this submission is for.                                                                  |
| `creator_user_id`        | `BIGINT`                                   | `NOT NULL`, `FK to users.id`             | The ID of the creator who submitted this content.                                                              |
| `folder_id`              | `BIGINT`                                   | `NOT NULL`, `FK to folders.id`           | The ID of the folder containing the content submitted for the request.                                         |
| `status`                 | `ENUM('pending_review', 'accepted', 'rejected')` | `NOT NULL`, `DEFAULT 'pending_review'` | The status of this specific submission.                                                                        |
| `balance_transaction_id` | `BIGINT`                                   | `NULL`, `FK to balance_transactions.id`  | Stores the ID of the credit transfer transaction. It's filled only when the `status` is changed to `accepted`. |
| `submitted_at`           | `TIMESTAMP`                                | `DEFAULT CURRENT_TIMESTAMP`            | The timestamp when the submission was made.                                                                    |
| `reviewed_at`            | `TIMESTAMP`                                | `NULL`                                 | The timestamp when the requester accepted or rejected the submission.                                          |

## Indexes

- A composite index on `(request_id, creator_user_id)` to quickly find submissions for a request by a specific creator.
- An index on `creator_user_id` to quickly find all submissions made by a creator.
- An index on `status` to filter submissions awaiting review.

## Workflow Notes

- A new row is created here every time a creator submits a folder for a request.
- The requester can accept multiple submissions for the same `request_id`.
- When a submission's `status` is updated to `accepted`:
    1. A financial transaction is triggered: the `requests.reward_amount` is debited from the requester and credited to the `creator_user_id`.
    2. The resulting transaction ID is stored in `balance_transaction_id`.
    3. The requester is granted access to the `folder_id` (e.g., by adding a row to `folder_purchases`).
- When a submission's `status` is updated to `rejected`, no financial transaction occurs.
