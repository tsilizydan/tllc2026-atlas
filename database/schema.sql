-- =================================================================
-- TSILIZY CORE - Database Schema
-- MySQL 8.0+ / MariaDB 10.5+
-- =================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- -----------------------------------------------------------------
-- AUTHENTICATION & AUTHORIZATION
-- -----------------------------------------------------------------

-- Roles table
CREATE TABLE IF NOT EXISTS `roles` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(50) NOT NULL,
    `slug` VARCHAR(50) NOT NULL UNIQUE,
    `description` VARCHAR(255) NULL,
    `permissions` JSON NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Users table
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `password_hash` VARCHAR(255) NOT NULL,
    `role_id` INT UNSIGNED NOT NULL,
    `first_name` VARCHAR(50) NOT NULL,
    `last_name` VARCHAR(50) NOT NULL,
    `avatar` VARCHAR(255) NULL,
    `is_active` TINYINT(1) DEFAULT 1,
    `remember_token` VARCHAR(255) NULL,
    `remember_expires` DATETIME NULL,
    `last_login` DATETIME NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Login attempts (for throttling)
CREATE TABLE IF NOT EXISTS `login_attempts` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `email` VARCHAR(100) NOT NULL,
    `ip_address` VARCHAR(45) NULL,
    `attempted_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_email_attempted` (`email`, `attempted_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Activity logs
CREATE TABLE IF NOT EXISTS `activity_logs` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED NULL,
    `action` VARCHAR(50) NOT NULL,
    `entity_type` VARCHAR(50) NOT NULL,
    `entity_id` INT UNSIGNED NULL,
    `ip_address` VARCHAR(45) NULL,
    `user_agent` TEXT NULL,
    `details` JSON NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_user_action` (`user_id`, `action`),
    INDEX `idx_entity` (`entity_type`, `entity_id`),
    INDEX `idx_created` (`created_at`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------
-- COMPANY CONFIGURATION
-- -----------------------------------------------------------------

-- Company profile
CREATE TABLE IF NOT EXISTS `company_profile` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `company_name` VARCHAR(100) NOT NULL DEFAULT 'TSILIZY LLC',
    `legal_name` VARCHAR(150) NULL,
    `tax_id` VARCHAR(50) NULL,
    `registration_number` VARCHAR(50) NULL,
    `address` TEXT NULL,
    `city` VARCHAR(100) NULL,
    `country` VARCHAR(100) NULL,
    `phone` VARCHAR(30) NULL,
    `email` VARCHAR(100) NULL,
    `website` VARCHAR(255) NULL,
    `logo_path` VARCHAR(255) NULL,
    `favicon_path` VARCHAR(255) NULL,
    `primary_color` VARCHAR(7) DEFAULT '#C9A227',
    `secondary_color` VARCHAR(7) DEFAULT '#000000',
    `social_facebook` VARCHAR(255) NULL,
    `social_twitter` VARCHAR(255) NULL,
    `social_linkedin` VARCHAR(255) NULL,
    `social_instagram` VARCHAR(255) NULL,
    `footer_text` TEXT NULL,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Services
CREATE TABLE IF NOT EXISTS `services` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `description` TEXT NULL,
    `icon` VARCHAR(100) NULL,
    `price_range` VARCHAR(100) NULL,
    `is_active` TINYINT(1) DEFAULT 1,
    `sort_order` INT DEFAULT 0,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------
-- CLIENT MANAGEMENT
-- -----------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `clients` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `company_name` VARCHAR(150) NOT NULL,
    `contact_name` VARCHAR(100) NULL,
    `email` VARCHAR(100) NULL,
    `phone` VARCHAR(30) NULL,
    `address` TEXT NULL,
    `city` VARCHAR(100) NULL,
    `country` VARCHAR(100) NULL,
    `website` VARCHAR(255) NULL,
    `tax_id` VARCHAR(50) NULL,
    `notes` TEXT NULL,
    `status` ENUM('active', 'inactive') DEFAULT 'active',
    `is_archived` TINYINT(1) DEFAULT 0,
    `archived_at` DATETIME NULL,
    `archived_by` INT UNSIGNED NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_status` (`status`),
    INDEX `idx_archived` (`is_archived`),
    FOREIGN KEY (`archived_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------
-- PROJECT MANAGEMENT
-- -----------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `projects` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `client_id` INT UNSIGNED NULL,
    `name` VARCHAR(150) NOT NULL,
    `description` TEXT NULL,
    `status` ENUM('planning', 'active', 'on_hold', 'completed') DEFAULT 'planning',
    `start_date` DATE NULL,
    `end_date` DATE NULL,
    `budget` DECIMAL(15, 2) NULL,
    `is_archived` TINYINT(1) DEFAULT 0,
    `archived_at` DATETIME NULL,
    `archived_by` INT UNSIGNED NULL,
    `created_by` INT UNSIGNED NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_client` (`client_id`),
    INDEX `idx_status` (`status`),
    INDEX `idx_archived` (`is_archived`),
    FOREIGN KEY (`client_id`) REFERENCES `clients`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`archived_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tasks
CREATE TABLE IF NOT EXISTS `tasks` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `project_id` INT UNSIGNED NOT NULL,
    `title` VARCHAR(200) NOT NULL,
    `description` TEXT NULL,
    `status` ENUM('todo', 'in_progress', 'review', 'done') DEFAULT 'todo',
    `priority` ENUM('low', 'medium', 'high', 'urgent') DEFAULT 'medium',
    `assigned_to` INT UNSIGNED NULL,
    `due_date` DATE NULL,
    `completed_at` DATETIME NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_project` (`project_id`),
    INDEX `idx_status` (`status`),
    INDEX `idx_assigned` (`assigned_to`),
    FOREIGN KEY (`project_id`) REFERENCES `projects`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`assigned_to`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Milestones
CREATE TABLE IF NOT EXISTS `milestones` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `project_id` INT UNSIGNED NOT NULL,
    `title` VARCHAR(150) NOT NULL,
    `description` TEXT NULL,
    `due_date` DATE NULL,
    `status` ENUM('pending', 'achieved') DEFAULT 'pending',
    `achieved_at` DATETIME NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_project` (`project_id`),
    INDEX `idx_status` (`status`),
    FOREIGN KEY (`project_id`) REFERENCES `projects`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------
-- INVOICE MANAGEMENT
-- -----------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `invoices` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `invoice_number` VARCHAR(50) NOT NULL UNIQUE,
    `client_id` INT UNSIGNED NULL,
    `project_id` INT UNSIGNED NULL,
    `issue_date` DATE NOT NULL,
    `due_date` DATE NOT NULL,
    `status` ENUM('draft', 'sent', 'paid', 'overdue', 'cancelled') DEFAULT 'draft',
    `subtotal` DECIMAL(15, 2) DEFAULT 0.00,
    `tax_rate` DECIMAL(5, 2) DEFAULT 0.00,
    `tax_amount` DECIMAL(15, 2) DEFAULT 0.00,
    `discount` DECIMAL(15, 2) DEFAULT 0.00,
    `total` DECIMAL(15, 2) DEFAULT 0.00,
    `currency` VARCHAR(3) DEFAULT 'USD',
    `notes` TEXT NULL,
    `terms` TEXT NULL,
    `is_archived` TINYINT(1) DEFAULT 0,
    `archived_at` DATETIME NULL,
    `archived_by` INT UNSIGNED NULL,
    `created_by` INT UNSIGNED NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_client` (`client_id`),
    INDEX `idx_project` (`project_id`),
    INDEX `idx_status` (`status`),
    INDEX `idx_archived` (`is_archived`),
    FOREIGN KEY (`client_id`) REFERENCES `clients`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`project_id`) REFERENCES `projects`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`archived_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Invoice items
CREATE TABLE IF NOT EXISTS `invoice_items` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `invoice_id` INT UNSIGNED NOT NULL,
    `description` VARCHAR(500) NOT NULL,
    `quantity` DECIMAL(10, 2) DEFAULT 1.00,
    `unit_price` DECIMAL(15, 2) DEFAULT 0.00,
    `total` DECIMAL(15, 2) DEFAULT 0.00,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_invoice` (`invoice_id`),
    FOREIGN KEY (`invoice_id`) REFERENCES `invoices`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------
-- HR MANAGEMENT
-- -----------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `employees` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `employee_code` VARCHAR(20) NOT NULL UNIQUE,
    `first_name` VARCHAR(50) NOT NULL,
    `last_name` VARCHAR(50) NOT NULL,
    `email` VARCHAR(100) NULL,
    `phone` VARCHAR(30) NULL,
    `position` VARCHAR(100) NULL,
    `department` VARCHAR(100) NULL,
    `hire_date` DATE NULL,
    `salary` DECIMAL(15, 2) NULL,
    `status` ENUM('active', 'inactive') DEFAULT 'active',
    `address` TEXT NULL,
    `emergency_contact` VARCHAR(200) NULL,
    `notes` TEXT NULL,
    `is_archived` TINYINT(1) DEFAULT 0,
    `archived_at` DATETIME NULL,
    `archived_by` INT UNSIGNED NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_status` (`status`),
    INDEX `idx_archived` (`is_archived`),
    FOREIGN KEY (`archived_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Paychecks
CREATE TABLE IF NOT EXISTS `paychecks` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `employee_id` INT UNSIGNED NOT NULL,
    `pay_period_start` DATE NOT NULL,
    `pay_period_end` DATE NOT NULL,
    `base_salary` DECIMAL(15, 2) DEFAULT 0.00,
    `bonuses` DECIMAL(15, 2) DEFAULT 0.00,
    `deductions` DECIMAL(15, 2) DEFAULT 0.00,
    `net_pay` DECIMAL(15, 2) DEFAULT 0.00,
    `payment_date` DATE NULL,
    `payment_method` VARCHAR(50) NULL,
    `status` ENUM('pending', 'paid') DEFAULT 'pending',
    `notes` TEXT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_employee` (`employee_id`),
    INDEX `idx_status` (`status`),
    INDEX `idx_period` (`pay_period_start`, `pay_period_end`),
    FOREIGN KEY (`employee_id`) REFERENCES `employees`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------
-- CONTRACT MANAGEMENT
-- -----------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `contracts` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `contract_number` VARCHAR(50) NOT NULL UNIQUE,
    `title` VARCHAR(200) NOT NULL,
    `client_id` INT UNSIGNED NULL,
    `partner_id` INT UNSIGNED NULL,
    `type` ENUM('service', 'partnership', 'employment', 'nda', 'other') DEFAULT 'service',
    `start_date` DATE NULL,
    `end_date` DATE NULL,
    `value` DECIMAL(15, 2) NULL,
    `status` ENUM('draft', 'active', 'completed', 'terminated') DEFAULT 'draft',
    `terms` TEXT NULL,
    `document_path` VARCHAR(255) NULL,
    `is_archived` TINYINT(1) DEFAULT 0,
    `archived_at` DATETIME NULL,
    `archived_by` INT UNSIGNED NULL,
    `created_by` INT UNSIGNED NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_client` (`client_id`),
    INDEX `idx_partner` (`partner_id`),
    INDEX `idx_status` (`status`),
    INDEX `idx_archived` (`is_archived`),
    FOREIGN KEY (`client_id`) REFERENCES `clients`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`archived_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------
-- PARTNER MANAGEMENT
-- -----------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `partners` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `company_name` VARCHAR(150) NOT NULL,
    `contact_name` VARCHAR(100) NULL,
    `email` VARCHAR(100) NULL,
    `phone` VARCHAR(30) NULL,
    `address` TEXT NULL,
    `website` VARCHAR(255) NULL,
    `partnership_type` VARCHAR(100) NULL,
    `notes` TEXT NULL,
    `status` ENUM('active', 'inactive') DEFAULT 'active',
    `is_archived` TINYINT(1) DEFAULT 0,
    `archived_at` DATETIME NULL,
    `archived_by` INT UNSIGNED NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_status` (`status`),
    INDEX `idx_archived` (`is_archived`),
    FOREIGN KEY (`archived_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add partner foreign key to contracts (after partners table exists)
ALTER TABLE `contracts` ADD CONSTRAINT `fk_contracts_partner` 
    FOREIGN KEY (`partner_id`) REFERENCES `partners`(`id`) ON DELETE SET NULL;

-- -----------------------------------------------------------------
-- FINANCE MANAGEMENT
-- -----------------------------------------------------------------

-- Bank accounts
CREATE TABLE IF NOT EXISTS `bank_accounts` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `bank_name` VARCHAR(100) NOT NULL,
    `account_name` VARCHAR(100) NOT NULL,
    `account_number` VARCHAR(50) NULL,
    `account_type` VARCHAR(50) NULL,
    `balance` DECIMAL(15, 2) DEFAULT 0.00,
    `is_primary` TINYINT(1) DEFAULT 0,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Payment methods
CREATE TABLE IF NOT EXISTS `payment_methods` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `type` ENUM('cash', 'bank_transfer', 'credit_card', 'paypal', 'other') DEFAULT 'other',
    `details` TEXT NULL,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Income
CREATE TABLE IF NOT EXISTS `incomes` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `category` VARCHAR(100) NOT NULL,
    `description` TEXT NULL,
    `amount` DECIMAL(15, 2) NOT NULL,
    `date` DATE NOT NULL,
    `client_id` INT UNSIGNED NULL,
    `invoice_id` INT UNSIGNED NULL,
    `payment_method_id` INT UNSIGNED NULL,
    `notes` TEXT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_category` (`category`),
    INDEX `idx_date` (`date`),
    INDEX `idx_client` (`client_id`),
    FOREIGN KEY (`client_id`) REFERENCES `clients`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`invoice_id`) REFERENCES `invoices`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Expenses
CREATE TABLE IF NOT EXISTS `expenses` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `category` VARCHAR(100) NOT NULL,
    `description` TEXT NULL,
    `amount` DECIMAL(15, 2) NOT NULL,
    `date` DATE NOT NULL,
    `vendor` VARCHAR(150) NULL,
    `payment_method_id` INT UNSIGNED NULL,
    `receipt_path` VARCHAR(255) NULL,
    `notes` TEXT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_category` (`category`),
    INDEX `idx_date` (`date`),
    FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------
-- SEED DATA
-- -----------------------------------------------------------------

-- Insert default roles
INSERT INTO `roles` (`name`, `slug`, `description`, `permissions`) VALUES
('Super Administrator', 'super_admin', 'Full system access with user management', '{"invoices":["view","create","edit","delete","print","archive"],"clients":["view","create","edit","delete","print"],"projects":["view","create","edit","delete","print","archive"],"hr":["view","create","edit","delete","print","archive"],"contracts":["view","create","edit","delete","print","archive"],"partners":["view","create","edit","delete","print","archive"],"finance":["view","create","edit","delete","print","reports"],"company":["view","edit"],"users":["view","create","edit","delete"],"logs":["view"]}'),
('Administrator', 'admin', 'All modules except system configuration', '{"invoices":["view","create","edit","delete","print","archive"],"clients":["view","create","edit","delete","print"],"projects":["view","create","edit","delete","print","archive"],"hr":["view","create","edit","delete","print","archive"],"contracts":["view","create","edit","delete","print","archive"],"partners":["view","create","edit","delete","print","archive"],"finance":["view","create","edit","delete","print","reports"],"company":["view"],"users":["view"]}'),
('Manager', 'manager', 'Projects, clients, contracts, and reports', '{"invoices":["view","create","edit","print"],"clients":["view","create","edit","print"],"projects":["view","create","edit","print"],"contracts":["view","create","edit","print"],"partners":["view","print"],"finance":["view","reports"],"company":["view"]}'),
('HR Manager', 'hr_manager', 'HR module, employee records, and paychecks', '{"hr":["view","create","edit","delete","print","archive"],"clients":["view"],"projects":["view"],"company":["view"]}'),
('Finance', 'finance', 'Finance module, invoices, and reports', '{"invoices":["view","create","edit","print","archive"],"clients":["view","print"],"finance":["view","create","edit","delete","print","reports"],"company":["view"]}'),
('Staff', 'staff', 'Read-only access to assigned items', '{"invoices":["view"],"clients":["view"],"projects":["view"],"company":["view"]}');

-- Insert default admin user (password: Admin@123)
INSERT INTO `users` (`username`, `email`, `password_hash`, `role_id`, `first_name`, `last_name`, `is_active`) VALUES
('admin', 'admin@tsilizy.com', '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewKyDAQC7s5Rm1GO', 1, 'System', 'Administrator', 1);

-- Insert default company profile
INSERT INTO `company_profile` (`company_name`, `legal_name`, `email`, `primary_color`, `secondary_color`) VALUES
('TSILIZY LLC', 'TSILIZY Limited Liability Company', 'info@tsilizy.com', '#C9A227', '#000000');

-- Insert default payment methods
INSERT INTO `payment_methods` (`name`, `type`, `is_active`) VALUES
('Cash', 'cash', 1),
('Bank Transfer', 'bank_transfer', 1),
('Credit Card', 'credit_card', 1),
('PayPal', 'paypal', 1);

SET FOREIGN_KEY_CHECKS = 1;
