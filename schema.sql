-- Database schema for the teleXweb project
-- This schema reflects all the design decisions made during the planning phase.

--
-- Table structure for table `users`
--
CREATE TABLE `users` (
  `id` BIGINT UNSIGNED NOT NULL COMMENT 'The unique ID of the Telegram user, taken from Telegram.',
  `first_name` VARCHAR(255) DEFAULT NULL COMMENT 'The user''s first name from Telegram.',
  `last_name` VARCHAR(255) DEFAULT NULL COMMENT 'The user''s last name from Telegram.',
  `username` VARCHAR(255) DEFAULT NULL COMMENT 'The user''s Telegram username (@username).',
  `codename` VARCHAR(50) DEFAULT NULL COMMENT 'A unique, privacy-preserving name for the user.',
  `status` ENUM('active','blocked','deleted') NOT NULL DEFAULT 'active' COMMENT 'The current status of the user.',
  `role` VARCHAR(50) NOT NULL DEFAULT 'viewer' COMMENT 'User role: admin, editor, viewer.',
  `language_code` VARCHAR(10) NOT NULL DEFAULT 'en' COMMENT 'User-preferred language code.',
  `has_completed_onboarding` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Flag for tutorial completion.',

  `user_level` INT UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Current level of the user.',
  `achievement_points` INT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Points accumulated by the user for achievements.',
  `balance` DECIMAL(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Current balance of the user.',
  `timezone` VARCHAR(64) NOT NULL DEFAULT 'UTC' COMMENT 'User-preferred timezone.',
  `last_activity_at` DATETIME DEFAULT NULL COMMENT 'Timestamp of the user''s last interaction with the bot/web.',

  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Timestamp when the user first interacted with the bot.',
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Timestamp of the user''s last interaction.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_codename` (`codename`),
  INDEX `idx_username` (`username`),
  INDEX `idx_role` (`role`),
  INDEX `idx_status` (`status`),
  INDEX `idx_user_level` (`user_level`),
  INDEX `idx_achievement_points` (`achievement_points`),

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stores information about Telegram users.';



--
-- Table structure for table `folders`
--
CREATE TABLE `folders` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL COMMENT 'Foreign key to the users table.',
  `parent_folder_id` INT UNSIGNED DEFAULT NULL COMMENT 'ID of the parent folder, if this is a subfolder.',
  `code` VARCHAR(12) NOT NULL COMMENT 'Unique short code for shareable links.',
  `folder_name` VARCHAR(255) NOT NULL COMMENT 'Name of the folder.',
  `description` TEXT DEFAULT NULL COMMENT 'Optional description for the folder.',
  `tags` VARCHAR(255) DEFAULT NULL COMMENT 'Comma-separated list of user-defined tags for categorization.',
  `folder_size` BIGINT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Aggregated size of all files (metadata) within the folder in bytes.',
  `is_favorited` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Flag to mark a folder as a favorite/pinned (0 = no, 1 = yes).',
  `price` DECIMAL(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Price of the folder if it''s for sale.',
  `is_for_sale` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Flag indicating if the folder is listed for sale (0 = no, 1 = yes).',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Timestamp when the folder was created.',
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Timestamp when the folder was last updated.',
  `deleted_at` DATETIME DEFAULT NULL COMMENT 'Timestamp for soft-deletion of the folder.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_user_folder_name` (`user_id`, `folder_name`),
  UNIQUE KEY `uq_folder_code` (`code`),
  INDEX `idx_user_id` (`user_id`),
  INDEX `idx_parent_folder_id` (`parent_folder_id`),
  INDEX `idx_is_favorited` (`is_favorited`),
  CONSTRAINT `fk_folders_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_folders_parent_folder_id` FOREIGN KEY (`parent_folder_id`) REFERENCES `folders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stores user-defined folders for organizing file metadata.';

--
-- Table structure for table `files`
--
CREATE TABLE `files` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `telegram_user_id` BIGINT UNSIGNED NOT NULL COMMENT 'Foreign key to the users table.',
  `folder_id` INT UNSIGNED DEFAULT NULL COMMENT 'ID of the folder this file belongs to. Foreign key to the folders table.',
  `file_unique_id` VARCHAR(255) NOT NULL COMMENT 'Unique ID of the file content from Telegram. Used to prevent content duplication.',
  `media_group_id` VARCHAR(255) DEFAULT NULL COMMENT 'ID of the media group if the file is part of an album.',
  `storage_channel_id` BIGINT NOT NULL COMMENT 'ID of the Telegram channel where the file is copied.',
  `storage_message_id` BIGINT NOT NULL COMMENT 'Message ID of the copied file within the storage channel.',
  `telegram_file_id` VARCHAR(255) NOT NULL COMMENT 'File ID from Telegram, used for initial copy operation.',
  `thumbnail_file_id` VARCHAR(255) DEFAULT NULL COMMENT 'File ID for the thumbnail, for image previews.',
  `file_name` VARCHAR(255) DEFAULT NULL COMMENT 'Sanitized file name for display.',
  `original_file_name` VARCHAR(255) DEFAULT NULL COMMENT 'Original name of the file as sent by the user.',
  `file_size` INT DEFAULT NULL COMMENT 'File size in bytes.',
  `mime_type` VARCHAR(100) DEFAULT NULL COMMENT 'MIME type of the file.',
  `is_favorited` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Flag to mark a file as a favorite (0 = no, 1 = yes).',
  `process_status` ENUM('pending','processed','indexed','failed') NOT NULL DEFAULT 'pending' COMMENT 'Current processing status of the file metadata.',
  `webhook_reliability_status` ENUM('success','failed','retried') NOT NULL DEFAULT 'success' COMMENT 'Status of the webhook delivery for this file.',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Timestamp when the file metadata record was created.',
  `deleted_at` DATETIME DEFAULT NULL COMMENT 'Timestamp for soft-deletes.',
  PRIMARY KEY (`id`),
  INDEX `idx_telegram_user_id` (`telegram_user_id`),
  INDEX `idx_folder_id` (`folder_id`),
  UNIQUE KEY `uq_file_unique_id` (`file_unique_id`),
  UNIQUE KEY `uq_storage_location` (`storage_channel_id`, `storage_message_id`),
  INDEX `idx_media_group_id` (`media_group_id`),
  INDEX `idx_mime_type` (`mime_type`),
  INDEX `idx_created_at` (`created_at`),
  INDEX `idx_is_favorited` (`is_favorited`),
  INDEX `idx_process_status` (`process_status`),
  INDEX `idx_webhook_reliability_status` (`webhook_reliability_status`),
  CONSTRAINT `fk_files_telegram_user_id` FOREIGN KEY (`telegram_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_files_folder_id` FOREIGN KEY (`folder_id`) REFERENCES `folders` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stores metadata for files received via Telegram bot.';

--
-- Table structure for table `notification_rules`
--
CREATE TABLE `notification_rules` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL COMMENT 'Foreign key to the users table.',
  `rule_name` VARCHAR(255) NOT NULL COMMENT 'A user-friendly name for the notification rule.',
  `trigger_type` VARCHAR(50) NOT NULL COMMENT 'The type of event that triggers the notification (e.g., file_tag_match, new_comment, achievement_unlocked).',
  `trigger_config_json` JSON DEFAULT NULL COMMENT 'JSON configuration for the trigger (e.g., {"tag": "urgent"} for file_tag_match).',
  `is_active` TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'Flag to enable or disable the rule.',
  `template_id` INT UNSIGNED NOT NULL COMMENT 'ID of the notification template to use.',
  `throttle_id` BIGINT UNSIGNED DEFAULT NULL COMMENT 'ID of the notification throttle setting for this rule.',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Timestamp when the notification rule was created.',
  PRIMARY KEY (`id`),
  INDEX `idx_user_id` (`user_id`),
  INDEX `idx_trigger_type` (`trigger_type`),
  INDEX `idx_template_id` (`template_id`),
  INDEX `idx_throttle_id` (`throttle_id`),
  CONSTRAINT `fk_rules_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_rules_template_id` FOREIGN KEY (`template_id`) REFERENCES `notification_templates` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_rules_throttle_id` FOREIGN KEY (`throttle_id`) REFERENCES `notification_throttles` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stores custom notification rules set by users.';

--
-- Table structure for table `notification_throttles`
--
CREATE TABLE `notification_throttles` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL COMMENT 'ID of the user receiving notifications.',
  `notification_type` VARCHAR(50) NOT NULL COMMENT 'Type of notification being throttled.',
  `last_sent_at` DATETIME DEFAULT NULL COMMENT 'Timestamp of the last successful notification send.',
  `send_count` INT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Number of notifications sent within the current throttling period.',
  `reset_at` DATETIME DEFAULT NULL COMMENT 'Timestamp when the send_count should be reset.',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Timestamp when the throttle entry was first created.',
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Timestamp when the entry was last updated.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_user_notification_type` (`user_id`, `notification_type`),
  INDEX `idx_user_id` (`user_id`),
  INDEX `idx_notification_type` (`notification_type`),
  CONSTRAINT `fk_notification_throttles_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tracks notification sending activity for throttling purposes.';

--
-- Table structure for table `notification_templates`
--
CREATE TABLE `notification_templates` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL COMMENT 'A unique name for the template.',
  `template_content` TEXT NOT NULL COMMENT 'The message content with variable placeholders.',
  `variables_json` JSON DEFAULT NULL COMMENT 'JSON array of expected variables for substitution.',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Timestamp when the template was created.',
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Timestamp when the template was last updated.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_template_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stores customizable notification message templates.';

--
-- Table structure for table `tags`
--
CREATE TABLE `tags` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `tag_name` VARCHAR(50) NOT NULL COMMENT 'The unique name of the tag.',
  `created_by_user_id` BIGINT UNSIGNED DEFAULT NULL COMMENT 'ID of the user who first created this tag.',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Timestamp when the tag was created.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_tag_name` (`tag_name`),
  INDEX `idx_created_by_user_id` (`created_by_user_id`),
  CONSTRAINT `fk_tags_created_by_user_id` FOREIGN KEY (`created_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stores unique tag names for the system.';

--
-- Table structure for table `folder_tags`
--
CREATE TABLE `folder_tags` (
  `folder_id` INT UNSIGNED NOT NULL,
  `tag_id` INT UNSIGNED NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Timestamp when the tag was associated with the folder.',
  PRIMARY KEY (`folder_id`, `tag_id`),
  INDEX `idx_folder_id` (`folder_id`),
  INDEX `idx_tag_id` (`tag_id`),
  CONSTRAINT `fk_folder_tags_folder_id` FOREIGN KEY (`folder_id`) REFERENCES `folders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_folder_tags_tag_id` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Junction table for folders and tags.';

--
-- Table structure for table `folder_reviews`
--
CREATE TABLE `folder_reviews` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `folder_id` INT UNSIGNED NOT NULL COMMENT 'ID of the folder being reviewed.',
  `user_id` BIGINT UNSIGNED NOT NULL COMMENT 'ID of the user who submitted the review.',
  `rating` TINYINT UNSIGNED NOT NULL COMMENT 'Star rating (1-5).',
  `review_text` TEXT DEFAULT NULL COMMENT 'Descriptive review text.',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Timestamp when the review was created.',
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Timestamp when the review was last updated.',
  `deleted_at` DATETIME DEFAULT NULL COMMENT 'Timestamp for soft-deletion of the review.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_folder_user_review` (`folder_id`, `user_id`),
  INDEX `idx_folder_id` (`folder_id`),
  INDEX `idx_user_id` (`user_id`),
  CONSTRAINT `fk_folder_reviews_folder_id` FOREIGN KEY (`folder_id`) REFERENCES `folders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_folder_reviews_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `chk_rating_range` CHECK (`rating` BETWEEN 1 AND 5)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stores user ratings and descriptive reviews for folders.';

--
-- Table structure for table `smart_collection_rules`
--
CREATE TABLE `smart_collection_rules` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL COMMENT 'Foreign key to the users table.',
  `rule_name` VARCHAR(255) NOT NULL COMMENT 'Name of the smart collection (e.g., "Last 7 days").',
  `rule_type` VARCHAR(50) NOT NULL COMMENT 'Type of rule (e.g., "last_n_days", "by_tag_frequency").',
  `rule_parameters_json` JSON NOT NULL COMMENT 'JSON object containing parameters for the rule.',
  `is_active` TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'Flag to enable or disable the smart collection.',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Timestamp when the rule was created.',
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Timestamp when the rule was last updated.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_user_rule_name` (`user_id`, `rule_name`),
  INDEX `idx_user_id` (`user_id`),
  CONSTRAINT `fk_smart_collection_rules_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stores definitions for user-created smart collections.';

--
-- Table structure for table `audit_logs`
--
CREATE TABLE `audit_logs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED DEFAULT NULL COMMENT 'ID of the user who performed the action (nullable for system actions).',
  `action` VARCHAR(50) NOT NULL COMMENT 'Type of action performed (e.g., create, update, delete, login).',
  `entity_type` VARCHAR(50) NOT NULL COMMENT 'Type of entity affected (e.g., user, folder, file).',
  `entity_id` BIGINT UNSIGNED DEFAULT NULL COMMENT 'ID of the entity affected by the action (nullable if not specific).',
  `old_value_json` JSON DEFAULT NULL COMMENT 'JSON representation of the entity''s state before the action.',
  `new_value_json` JSON DEFAULT NULL COMMENT 'JSON representation of the entity''s state after the action.',
  `ip_address` VARCHAR(45) DEFAULT NULL COMMENT 'IP address from which the action was performed.',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Timestamp when the action was performed.',
  PRIMARY KEY (`id`),
  INDEX `idx_user_id` (`user_id`),
  INDEX `idx_entity` (`entity_type`, `entity_id`),
  CONSTRAINT `fk_audit_logs_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tracks admin and significant user actions for auditing purposes.';

--
-- Table structure for table `failed_webhooks`
--
CREATE TABLE `failed_webhooks` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `webhook_payload_json` JSON NOT NULL COMMENT 'The complete JSON payload of the failed webhook.',
  `error_message` TEXT DEFAULT NULL COMMENT 'The error message that caused the failure.',
  `attempt_count` TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Number of retry attempts made so far.',
  `last_attempt_at` DATETIME DEFAULT NULL COMMENT 'Timestamp of the last retry attempt.',
  `status` ENUM('pending','retried','failed_permanently') NOT NULL DEFAULT 'pending' COMMENT 'Current status of the failed webhook.',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Timestamp when the webhook was first recorded as failed.',
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Timestamp when the entry was last updated (e.g., after a retry).',
  PRIMARY KEY (`id`),
  INDEX `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stores details of webhook attempts that failed processing.';

--
-- Table structure for table `access_logs`
--
CREATE TABLE `access_logs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED DEFAULT NULL COMMENT 'ID of the user who accessed the entity (nullable for public access).',
  `entity_type` ENUM('file','folder') NOT NULL COMMENT 'Type of entity that was accessed.',
  `entity_id` BIGINT UNSIGNED NOT NULL COMMENT 'ID of the entity that was accessed.',
  `accessed_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Timestamp when the entity was accessed.',
  PRIMARY KEY (`id`),
  INDEX `idx_user_id` (`user_id`),
  INDEX `idx_entity` (`entity_type`, `entity_id`),
  INDEX `idx_accessed_at` (`accessed_at`),
  CONSTRAINT `fk_access_logs_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tracks file and folder access for trending features.';

--
-- Table structure for table `folder_likes`
--
CREATE TABLE `folder_likes` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL COMMENT 'ID of the user who liked the folder.',
  `folder_id` BIGINT UNSIGNED NOT NULL COMMENT 'ID of the folder that was liked.',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Timestamp when the like was given.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_user_folder_like` (`user_id`, `folder_id`),
  INDEX `idx_folder_id` (`folder_id`),
  CONSTRAINT `fk_folder_likes_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_folder_likes_folder_id` FOREIGN KEY (`folder_id`) REFERENCES `folders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tracks user likes on folders.';

--
-- Table structure for table `public_collections`
--
CREATE TABLE `public_collections` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `admin_id` BIGINT UNSIGNED NOT NULL COMMENT 'ID of the admin user who created this collection.',
  `collection_name` VARCHAR(255) NOT NULL COMMENT 'Name of the public collection.',
  `description` TEXT DEFAULT NULL COMMENT 'A brief description of the collection.',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Timestamp when the collection was created.',
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Timestamp when the collection was last updated.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_collection_name` (`collection_name`),
  INDEX `idx_admin_id` (`admin_id`),
  CONSTRAINT `fk_public_collections_admin_id` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stores definitions for admin-curated public collections of folders.';

--
-- Table structure for table `public_collection_folders`
--
CREATE TABLE `public_collection_folders` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `public_collection_id` BIGINT UNSIGNED NOT NULL COMMENT 'ID of the public collection.',
  `folder_id` BIGINT UNSIGNED NOT NULL COMMENT 'ID of the folder added to the collection.',
  `added_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Timestamp when the folder was added to this collection.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_collection_folder` (`public_collection_id`, `folder_id`),
  INDEX `idx_folder_id` (`folder_id`),
  CONSTRAINT `fk_public_collection_folders_collection_id` FOREIGN KEY (`public_collection_id`) REFERENCES `public_collections` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_public_collection_folders_folder_id` FOREIGN KEY (`folder_id`) REFERENCES `folders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Links folders to public collections.';

--
-- Table structure for table `folder_comments`
--
CREATE TABLE `folder_comments` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `folder_id` BIGINT UNSIGNED NOT NULL COMMENT 'ID of the folder the comment belongs to.',
  `user_id` BIGINT UNSIGNED NOT NULL COMMENT 'ID of the user who made the comment.',
  `parent_comment_id` BIGINT UNSIGNED DEFAULT NULL COMMENT 'ID of the parent comment, for threaded replies.',
  `comment_text` TEXT NOT NULL COMMENT 'The content of the comment.',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Timestamp when the comment was created.',
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Timestamp when the comment was last updated.',
  `deleted_at` DATETIME DEFAULT NULL COMMENT 'Timestamp if the comment was soft-deleted.',
  PRIMARY KEY (`id`),
  INDEX `idx_folder_id` (`folder_id`),
  INDEX `idx_user_id` (`user_id`),
  INDEX `idx_parent_comment_id` (`parent_comment_id`),
  CONSTRAINT `fk_folder_comments_folder_id` FOREIGN KEY (`folder_id`) REFERENCES `folders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_folder_comments_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_folder_comments_parent_id` FOREIGN KEY (`parent_comment_id`) REFERENCES `folder_comments` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stores comments made on shared folders.';

--
-- Table structure for table `achievements`
--
CREATE TABLE `achievements` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL COMMENT 'Name of the achievement (e.g., "File Organizer").',
  `description` TEXT NOT NULL COMMENT 'A detailed description of the achievement.',
  `criteria_json` JSON NOT NULL COMMENT 'JSON object defining the criteria to earn this achievement.',
  `badge_icon_url` VARCHAR(255) DEFAULT NULL COMMENT 'URL to the badge icon image.',
  `xp_reward` INT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Experience points awarded for earning this achievement.',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Timestamp when the achievement definition was created.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_achievement_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Defines gamification badges and achievements.';

--
-- Table structure for table `user_achievements`
--
CREATE TABLE `user_achievements` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL COMMENT 'ID of the user who earned the achievement.',
  `achievement_id` INT UNSIGNED NOT NULL COMMENT 'ID of the achievement earned.',
  `achieved_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Timestamp when the achievement was earned.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_user_achievement` (`user_id`, `achievement_id`),
  INDEX `idx_achievement_id` (`achievement_id`),
  CONSTRAINT `fk_user_achievements_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_user_achievements_achievement_id` FOREIGN KEY (`achievement_id`) REFERENCES `achievements` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tracks achievements earned by users.';

--
-- Table structure for table `xp_transactions`
--
CREATE TABLE `xp_transactions` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL COMMENT 'ID of the user involved in the transaction.',
  `xp_amount` INT NOT NULL COMMENT 'Amount of XP gained or lost (can be positive or negative).',
  `reason` VARCHAR(255) NOT NULL COMMENT 'Description of why the XP was gained/lost.',
  `entity_type` VARCHAR(50) DEFAULT NULL COMMENT 'Type of entity related to the XP transaction.',
  `entity_id` BIGINT UNSIGNED DEFAULT NULL COMMENT 'ID of the entity related to the XP transaction.',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Timestamp when the XP transaction occurred.',
  PRIMARY KEY (`id`),
  INDEX `idx_user_id` (`user_id`),
  INDEX `idx_entity` (`entity_type`, `entity_id`),
  CONSTRAINT `fk_xp_transactions_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Records every instance of a user gaining or losing XP.';

--
-- Table structure for table `balance_transactions`
--
CREATE TABLE `balance_transactions` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL COMMENT 'ID of the user whose balance was affected.',
  `transaction_type` ENUM('top_up','deduction','purchase','refund') NOT NULL COMMENT 'Type of balance transaction.',
  `amount` DECIMAL(10,2) NOT NULL COMMENT 'The amount of balance change.',
  `description` TEXT NOT NULL COMMENT 'A brief description of the transaction.',
  `admin_id` BIGINT UNSIGNED DEFAULT NULL COMMENT 'ID of the admin who initiated a manual transaction.',
  `related_entity_type` VARCHAR(50) DEFAULT NULL COMMENT 'Type of entity related to the transaction.',
  `related_entity_id` BIGINT UNSIGNED DEFAULT NULL COMMENT 'ID of the entity related to the transaction.',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Timestamp when the transaction occurred.',
  PRIMARY KEY (`id`),
  INDEX `idx_user_id` (`user_id`),
  INDEX `idx_admin_id` (`admin_id`),
  INDEX `idx_transaction_type` (`transaction_type`),
  CONSTRAINT `fk_balance_transactions_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_balance_transactions_admin_id` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tracks all changes to user balances.';

--
-- Table structure for table `folder_purchases`
--
CREATE TABLE `folder_purchases` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `folder_id` INT UNSIGNED NOT NULL COMMENT 'ID of the folder that was purchased.',
  `buyer_user_id` BIGINT UNSIGNED NOT NULL COMMENT 'ID of the user who bought the folder.',
  `seller_user_id` BIGINT UNSIGNED NOT NULL COMMENT 'ID of the user who sold the folder.',
  `price_at_purchase` DECIMAL(10,2) NOT NULL COMMENT 'The price of the folder at the time of purchase.',
  `purchase_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Timestamp when the purchase occurred.',
  `balance_transaction_id` BIGINT UNSIGNED DEFAULT NULL COMMENT 'ID of the corresponding balance transaction.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_folder_buyer` (`folder_id`, `buyer_user_id`),
  INDEX `idx_folder_id` (`folder_id`),
  INDEX `idx_buyer_user_id` (`buyer_user_id`),
  INDEX `idx_seller_user_id` (`seller_user_id`),
  INDEX `idx_balance_transaction_id` (`balance_transaction_id`),
  CONSTRAINT `fk_folder_purchases_folder_id` FOREIGN KEY (`folder_id`) REFERENCES `folders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_folder_purchases_buyer_user_id` FOREIGN KEY (`buyer_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_folder_purchases_seller_user_id` FOREIGN KEY (`seller_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_folder_purchases_balance_transaction_id` FOREIGN KEY (`balance_transaction_id`) REFERENCES `balance_transactions` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tracks purchases of folders by users.';

--
-- Table structure for table `roles`
--
CREATE TABLE `roles` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `role_name` VARCHAR(50) NOT NULL COMMENT 'The name of the role (e.g., admin, editor, viewer).',
  `description` TEXT DEFAULT NULL COMMENT 'A brief description of the role''s responsibilities.',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Timestamp when the role was created.',
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Timestamp when the role was last updated.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_role_name` (`role_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stores user roles for permission management.';

--
-- Table structure for table `permissions`
--
CREATE TABLE `permissions` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `permission_name` VARCHAR(255) NOT NULL COMMENT 'The programmatic name of the permission (e.g., "edit_files").',
  `category` VARCHAR(100) DEFAULT 'general' COMMENT 'A category for grouping permissions (e.g., "files", "users").',
  `description` TEXT DEFAULT NULL COMMENT 'A user-friendly description of what this permission allows.',
  `is_active` TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'Whether the permission is currently active and can be assigned (0 = no, 1 = yes).',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Timestamp when the permission was created.',
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Timestamp when the permission was last updated.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_permission_name` (`permission_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Defines individual permissions that can be assigned to roles.';

--
-- Table structure for table `role_permissions`
--
CREATE TABLE `role_permissions` (
  `role_id` INT UNSIGNED NOT NULL,
  `permission_id` INT UNSIGNED NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Timestamp when the permission was granted to the role.',
  PRIMARY KEY (`role_id`, `permission_id`),
  INDEX `idx_role_id` (`role_id`),
  INDEX `idx_permission_id` (`permission_id`),
  CONSTRAINT `fk_role_permissions_role_id` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_role_permissions_permission_id` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Junction table to assign permissions to roles (many-to-many).';

--
-- Table structure for table `user_roles`
--
CREATE TABLE `user_roles` (
  `user_id` BIGINT UNSIGNED NOT NULL,
  `role_id` INT UNSIGNED NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`, `role_id`),
  INDEX `idx_user_id` (`user_id`),
  INDEX `idx_role_id` (`role_id`),
  CONSTRAINT `fk_user_roles_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_user_roles_role_id` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Junction table to assign roles to users (many-to-many).';


--
-- Insert default roles
--
INSERT INTO `roles` (`role_name`, `description`) VALUES
('admin', 'Administrator with full system access.'),
('editor', 'Can edit content and manage users.'),
('viewer', 'Can view content.'),
('user', 'A standard user with basic permissions.');
