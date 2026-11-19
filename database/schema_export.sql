-- ====================================================================
-- IT0049 (Web System Technologies) - Technical Formative Assessment 7
-- Student: Simoune Nicole
-- Project: Laravel POS (Forms, Validation, and File Upload - Liquid Glass)
-- File: schema_export.sql
-- ====================================================================
--
-- SUBMISSION REQUIREMENT NOTE:
-- "A link to a GitHub repository containing your raw project files, including
-- your database export or migration and seeder files."
--
-- DESIGN & ARCHITECTURAL SCOPE (TFA7 extends TFA6):
-- 1. Table `customers` (id, full_name, email UNIQUE, phone, timestamps)
-- 2. Table `users` (id, username UNIQUE, full_name, role, avatar, timestamps)
--
-- Populated with Simoune's 6 canonical sample records per table.
-- ====================================================================

-- --------------------------------------------------------------------
-- Table structure for table `customers`
-- --------------------------------------------------------------------
DROP TABLE IF EXISTS `customers`;
CREATE TABLE `customers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `customers_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- Dumping data for table `customers`
-- --------------------------------------------------------------------
LOCK TABLES `customers` WRITE;
INSERT INTO `customers` (`id`, `full_name`, `email`, `phone`, `created_at`, `updated_at`) VALUES
(1, 'Eleanor Vance', 'eleanor.vance@example.com', '+1 (555) 234-5678', NOW(), NOW()),
(2, 'Marcus Thorne', 'marcus.thorne@example.com', '+1 (555) 345-6789', NOW(), NOW()),
(3, 'Sophia Sterling', 'sophia.sterling@example.com', '+1 (555) 456-7890', NOW(), NOW()),
(4, 'Lucas Gallagher', 'lucas.gallagher@example.com', '+1 (555) 567-8901', NOW(), NOW()),
(5, 'Clara Delgado', 'clara.delgado@example.com', '+1 (555) 678-9012', NOW(), NOW()),
(6, 'Julian Hayes', 'julian.hayes@example.com', '+1 (555) 789-0123', NOW(), NOW());
UNLOCK TABLES;

-- --------------------------------------------------------------------
-- Table structure for table `users`
-- --------------------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'Staff Member',
  `avatar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- Dumping data for table `users`
-- --------------------------------------------------------------------
LOCK TABLES `users` WRITE;
INSERT INTO `users` (`id`, `username`, `full_name`, `role`, `avatar`, `created_at`, `updated_at`) VALUES
(1, 'clara.delgado', 'Clara Delgado', 'System Administrator', NULL, NOW(), NOW()),
(2, 'marcus.thorne', 'Marcus Thorne', 'Store Manager', NULL, NOW(), NOW()),
(3, 'eleanor.vance', 'Eleanor Vance', 'Head Cashier', NULL, NOW(), NOW()),
(4, 'lucas.gallagher', 'Lucas Gallagher', 'Cashier', NULL, NOW(), NOW()),
(5, 'sophia.sterling', 'Sophia Sterling', 'Inventory Specialist', NULL, NOW(), NOW()),
(6, 'julian.hayes', 'Julian Hayes', 'Cashier', NULL, NOW(), NOW());
UNLOCK TABLES;
