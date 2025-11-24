# Requests Table (`requests`)

This table stores all content requests, which can function as both public bounties (open to all creators) and direct requests (targeted to a specific creator).

## Columns

| Column Name              | Data Type                         | Constraints                              | Description                                                                                             |
| ------------------------ | --------------------------------- | ---------------------------------------- | ------------------------------------------------------------------------------------------------------- |
| `id`                     | `BIGINT`                          | `PRIMARY KEY`, `AUTO_INCREMENT`            | Unique identifier for the request.                                                                      |
| `requester_user_id`      | `BIGINT`                          | `NOT NULL`, `FK to users.id`               | The ID of the user who created the request.                                                             |
| `target_creator_user_id` | `BIGINT`                          | `NULL`, `FK to users.id`                   | The ID of the creator this request is specifically for. If `NULL`, it's a public bounty.                  |
| `title`                  | `VARCHAR(255)`                    | `NOT NULL`                               | The title of the request.                                                                               |
| `description`            | `TEXT`                            | `NOT NULL`                               | A detailed description of the content required.                                                         |
| `reward_amount`          | `DECIMAL(10,2)`                   | `NOT NULL`                               | The amount of credits the requester will pay for an accepted submission.                                |
| `deadline_at`            | `TIMESTAMP`                       | `NULL`                                   | The deadline for submissions. If `NULL`, the request is open-ended ("fleksibel").                         |
| `priority`               | `ENUM('urgent', 'normal', 'low')` | `NOT NULL`, `DEFAULT 'normal'`           | The priority level of the request.                                                                      |
| `type`                   | `ENUM('public_bounty', 'direct_request')` | `NOT NULL`                             | Determines if the request is a public bounty or a direct request to a specific creator.                 |
| `status`                 | `ENUM('open', 'closed', 'cancelled')` | `NOT NULL`, `DEFAULT 'open'`             | The overall status of the request. 'open' means submissions are welcome, 'closed' means it's finished. |
| `created_at`             | `TIMESTAMP`                       | `DEFAULT CURRENT_TIMESTAMP`              | The timestamp when the request was created.                                                             |

## Indexes

- An index on `requester_user_id` to quickly find all requests made by a user.
- An index on `target_creator_user_id` to quickly find all direct requests sent to a creator.
- A composite index on `(type, status)` to efficiently filter for public, open bounties.
- An index on `reward_amount` and `created_at` to support filtering on the public bounty page.

## Workflow Notes

- When a new request is created, no funds are held in escrow.
- If `target_creator_user_id` is set, `type` should be `direct_request`.
- If `target_creator_user_id` is `NULL`, `type` should be `public_bounty`.
- The actual fulfillment and financial transactions are handled via the `request_submissions` table.
