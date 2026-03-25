CREATE DATABASE IF NOT EXISTS `nameGeneratorTool`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `nameGeneratorTool`;

CREATE TABLE IF NOT EXISTS `wp_winner_photo_release_submissions` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `first_name` VARCHAR(120) NOT NULL,
  `last_name` VARCHAR(120) NOT NULL,
  `email` VARCHAR(190) NOT NULL,
  `phone` VARCHAR(40) NOT NULL,
  `address_1` VARCHAR(255) NOT NULL,
  `address_2` VARCHAR(255) NULL,
  `city` VARCHAR(120) NOT NULL,
  `province` VARCHAR(120) NOT NULL,
  `postal_code` VARCHAR(25) NOT NULL,
  `winner_photo_path` VARCHAR(255) NOT NULL,
  `status` ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `agreed_terms` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_created_at` (`created_at`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
