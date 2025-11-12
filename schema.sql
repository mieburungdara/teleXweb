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
  `role` VARCHAR(50) NOT NULL DEFAULT 'viewer' COMMENT 'User role: admin, editor, viewer.',
  `is_blocked` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Flag to block a user (0 = active, 1 = blocked).',
  `language` VARCHAR(10) NOT NULL DEFAULT 'en' COMMENT 'User-preferred language code.',
  `theme` VARCHAR(10) NOT NULL DEFAULT 'light' COMMENT 'User-preferred UI theme.',
  `has_completed_onboarding` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Flag for tutorial completion.',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_username` (`username`),
  INDEX `idx_role` (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `notification_rules`
--
CREATE TABLE `notification_rules` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `rule_name` VARCHAR(255) NOT NULL,
  `trigger_tag` VARCHAR(255) NOT NULL COMMENT 'The tag that triggers the notification.',
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_user_id` (`user_id`),
  CONSTRAINT `fk_rules_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
