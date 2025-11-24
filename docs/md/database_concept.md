# Database Concept (MySQL)

A single table will be used to store metadata for each file received.

**Table: `files`**

| Column Name          | Data Type    | Description                                                  | Notes                               |
| -------------------- | ------------ | ------------------------------------------------------------ | ----------------------------------- |
| `id`                 | `INT`        | Primary key, auto-increment.                                 | Consider `INT UNSIGNED` for optimization. |
| `telegram_user_id`   | `BIGINT`     | The unique ID of the Telegram user who sent the file.        | Indexed for quick lookups. Consider `BIGINT UNSIGNED`. |
| `folder_id`          | `INT UNSIGNED` | ID of the folder this file belongs to.                       | Nullable. Foreign key to `folders.id`. Indexed. |
| `file_unique_id`     | `VARCHAR(255)` | Unique ID of the file content from Telegram.                 | NOT NULL. UNIQUE. Used to prevent content duplication. |
| `media_group_id`     | `VARCHAR(255)` | ID of the media group if the file is part of an album.       | Nullable. Indexed. |
| `storage_channel_id` | `BIGINT`       | ID of the Telegram channel where the file is copied.         | NOT NULL. Indexed. |
| `storage_message_id` | `BIGINT`       | Message ID of the copied file within the storage channel.    | NOT NULL. Unique per `storage_channel_id`. |
| `telegram_file_id`   | `VARCHAR(255)` | The unique ID of the file instance from Telegram.            | Useful for initial copy operation. |
| `thumbnail_file_id`  | `VARCHAR(255)` | The `file_id` of the thumbnail for image previews.           | Nullable. Provided by Telegram for image files. |
| `file_name`          | `VARCHAR(255)` | The new, sanitized name of the file stored on the server.    | Review realistic max length for `VARCHAR`. |
| `original_file_name` | `VARCHAR(255)` | The original name of the file as sent by the user.           | Review realistic max length for `VARCHAR`. |
| `file_size`          | `INT`        | The size of the file in bytes.                               |                                     |
| `mime_type`          | `VARCHAR(100)` | The MIME type of the file.                                   | Indexed for filtering by type. Review realistic max length for `VARCHAR`. |
| `tags`               | `VARCHAR(255)` | User-defined tags for categorization.                        | Nullable. e.g., "work,report,q4". Review realistic max length for `VARCHAR`. |
| `is_favorited`       | `TINYINT(1)` | Flag to mark a file as a favorite (0 = no, 1 = yes).         | NOT NULL, DEFAULT 0. Indexed.       |
| `process_status`     | `ENUM('pending','processed','indexed','failed')` | Current processing status of the file metadata.              | NOT NULL, DEFAULT 'pending'. Indexed. |
| `webhook_reliability_status` | `ENUM('success','failed','retried')` | Status of the webhook delivery for this file.                | NOT NULL, DEFAULT 'success'. Indexed. |
| `created_at`         | `DATETIME`   | Timestamp when the file metadata record was created.         | Indexed for date-range queries.     |
| `deleted_at`         | `DATETIME`   | Timestamp when the record was soft-deleted.                  | Nullable. Used for soft deletes.    |

**Schema Comments:** All tables and columns in `schema.sql` will include descriptive comments for better documentation and understanding.

## Table Relationships

A second table, `users`, will be created to store user information.
A new `folders` table will be created to allow users to organize their file metadata.
A `tags` table and two junction tables (`file_tags`, `folder_tags`) will be created for a shared tagging system.
A `folder_reviews` table will be created to store user ratings and reviews for folders.
A `smart_collection_rules` table will be created to define auto-generated collections.
An `audit_logs` table will be created to track admin and significant user actions.
A `failed_webhooks` table will be created to store details of webhook attempts that failed processing.
An `access_logs` table will be created to track file and folder access for trending features.
A `folder_likes` table will be created to track user likes on folders.
A `public_collections` table will be created for admin-curated collections of folders.
A `public_collection_folders` junction table will link public collections to folders.
A `folder_comments` table will be created for threaded comments on shared folders.
An `achievements` table will define gamification badges.
A `user_achievements` table will track earned achievements.
An `xp_transactions` table will log all XP gains/losses.
A `notification_throttles` table will track notification sending activity for rate limiting.
A `notification_templates` table will store customizable notification messages.
A `subscriptions` table will manage user subscription details and payment history.
A `balance_transactions` table will log all changes to user balances.
A `folder_purchases` table will track all purchases of folders.

*   The `files.telegram_user_id` column will act as a **foreign key**.
*   It will reference the `id` column in the `users` table (`users.id`).
*   The `folders.user_id` column will act as a **foreign key** and reference `users.id`.
*   The `files.folder_id` column (newly added to the `files` table) will act as a **foreign key** and reference `folders.id`.
*   `tags.created_by_user_id` will reference `users.id`.
*   `folder_tags.folder_id` will reference `folders.id`, and `folder_tags.tag_id` will reference `tags.id`.
*   `folder_reviews.folder_id` will reference `folders.id`, and `folder_reviews.user_id` will reference `users.id`.
*   `smart_collection_rules.user_id` will reference `users.id`.
*   `audit_logs.user_id` will reference `users.id`.
*   `access_logs.user_id` will reference `users.id`.
*   `access_logs.entity_id` (when `entity_type` is 'file') will reference `files.id`.
*   `access_logs.entity_id` (when `entity_type` is 'folder') will reference `folders.id`.
*   `folder_likes.user_id` will reference `users.id`.
*   `folder_likes.folder_id` will reference `folders.id`.
*   `public_collections.admin_id` will reference `users.id`.
*   `public_collection_folders.public_collection_id` will reference `public_collections.id`.
*   `public_collection_folders.folder_id` will reference `folders.id`.
*   `folder_comments.folder_id` will reference `folders.id`.
*   `folder_comments.user_id` will reference `users.id`.
*   `folder_comments.parent_comment_id` will reference `folder_comments.id` (self-referencing).
*   `user_achievements.user_id` will reference `users.id`.
*   `user_achievements.achievement_id` will reference `achievements.id`.
*   `xp_transactions.user_id` will reference `users.id`.
*   `notification_rules.user_id` will reference `users.id`.
*   `notification_rules.template_id` will reference `notification_templates.id`.
*   `notification_rules.throttle_id` will reference `notification_throttles.id`.
*   `notification_throttles.user_id` will reference `users.id`.
*   `subscriptions.user_id` will reference `users.id`.
*   `balance_transactions.user_id` will reference `users.id`.
*   `balance_transactions.admin_id` will reference `users.id`.
*   `folder_purchases.folder_id` will reference `folders.id`.
*   `folder_purchases.buyer_user_id` will reference `users.id`.
*   `folder_purchases.seller_user_id` will reference `users.id`.
*   `folder_purchases.balance_transaction_id` will reference `balance_transactions.id`.
*   This relationship allows for joining the tables to fetch user details, folder information, file metadata, associated tags (via folders), user reviews for folders, dynamically generate smart collections, track system actions, manage failed webhook attempts, track access for trending content, record user likes on folders, curate public collections, enable threaded comments on shared folders, implement a full gamification system, manage advanced notification features, handle user subscriptions, audit balance changes, and track folder purchases.
*   Refer to `users_table_concept.md`, `folders_table_concept.md`, `files_table_concept.md`, `notification_rules_table_concept.md`, `tags_table_concept.md`, `folder_tags_table_concept.md`, `folder_reviews_table_concept.md`, `smart_collection_rules_table_concept.md`, `audit_logs_table_concept.md`, `failed_webhooks_table_concept.md`, `access_logs_table_concept.md`, `folder_likes_table_concept.md`, `public_collections_table_concept.md`, `public_collection_folders_table_concept.md`, `folder_comments_table_concept.md`, `achievements_table_concept.md`, `user_achievements_table_concept.md`, `xp_transactions_table_concept.md`, `notification_throttles_table_concept.md`, `notification_templates_table_concept.md`, `monetization_concept.md`, `balance_transactions_table_concept.md`, and `folder_purchases_table_concept.md` for the full structures of these tables.

