# Folder Purchases Table Concept (MySQL)

To track purchases of folders by users, a new `folder_purchases` table will be introduced. This table will record every successful purchase of a folder, linking the buyer, seller, folder, and the transaction details.

## Table: `folder_purchases`

This table tracks all purchases of folders.

| Column Name         | Data Type         | Description                                                  | Notes                                      |
|---------------------|-------------------|--------------------------------------------------------------|--------------------------------------------|
| `id`                | `BIGINT UNSIGNED` | Primary key for the purchase record.                         | Auto-incrementing.                         |
| `folder_id`         | `INT UNSIGNED`    | ID of the folder that was purchased.                         | Foreign key to `folders.id`. NOT NULL. Indexed. |
| `buyer_user_id`     | `BIGINT UNSIGNED` | ID of the user who bought the folder.                        | Foreign key to `users.id`. NOT NULL. Indexed. |
| `seller_user_id`    | `BIGINT UNSIGNED` | ID of the user who sold the folder.                          | Foreign key to `users.id`. NOT NULL. Indexed. |
| `price_at_purchase` | `DECIMAL(10,2)`   | The price of the folder at the time of purchase.             | NOT NULL.                                  |
| `purchase_date`     | `DATETIME`        | Timestamp when the purchase occurred.                        | Defaults to `CURRENT_TIMESTAMP`.           |
| `balance_transaction_id` | `BIGINT UNSIGNED` | ID of the corresponding balance transaction (deduction from buyer, addition to seller). | Nullable. Foreign key to `balance_transactions.id`. Indexed. |

### Relationships

*   `folder_purchases.folder_id` references `folders.id`.
*   `folder_purchases.buyer_user_id` references `users.id`.
*   `folder_purchases.seller_user_id` references `users.id`.
*   `folder_purchases.balance_transaction_id` references `balance_transactions.id`.
