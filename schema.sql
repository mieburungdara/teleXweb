-- Database schema for the teleXweb project
-- This schema reflects all the design decisions made during the planning phase.

--
-- Table structure for table `users`
--
CREATE TABLE `users` (
  `id` BIGINT UNSIGNED NOT NULL COMMENT 'The unique ID of the Telegram user, taken from Telegram.',
  `first_name` VARCHAR(255) DEFAULT NULL,
  `last_name` VARCHAR(255) DEFAULT NULL,
  `username` VARCHAR(255) DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `files`
--
CREATE TABLE `files` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `telegram_user_id` BIGINT UNSIGNED NOT NULL,
  `telegram_file_id` VARCHAR(255) NOT NULL COMMENT 'File ID from Telegram, used to re-fetch the file.',
  `thumbnail_file_id` VARCHAR(255) DEFAULT NULL COMMENT 'File ID for the thumbnail, for image previews.',
  `file_name` VARCHAR(255) DEFAULT NULL COMMENT 'Sanitized file name for display.',
  `original_file_name` VARCHAR(255) DEFAULT NULL,
  `file_size` INT DEFAULT NULL COMMENT 'File size in bytes.',
  `mime_type` VARCHAR(100) DEFAULT NULL,
  `tags` VARCHAR(255) DEFAULT NULL COMMENT 'Comma-separated list of user-defined tags.',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` DATETIME DEFAULT NULL COMMENT 'Timestamp for soft-deletes.',
  PRIMARY KEY (`id`),
  INDEX `idx_telegram_user_id` (`telegram_user_id`),
  INDEX `idx_mime_type` (`mime_type`),
  INDEX `idx_created_at` (`created_at`),
  CONSTRAINT `fk_files_telegram_user_id` FOREIGN KEY (`telegram_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
