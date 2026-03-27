-- Facilities / Asset Management Module
-- Run this migration to add asset tables

-- Asset categories
CREATE TABLE IF NOT EXISTS `asset_categories` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `slug` VARCHAR(100) NOT NULL UNIQUE,
    `description` TEXT NULL,
    `icon` VARCHAR(50) NULL,
    `sort_order` INT DEFAULT 0,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Assets
CREATE TABLE IF NOT EXISTS `assets` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `asset_tag` VARCHAR(50) NOT NULL UNIQUE,
    `name` VARCHAR(200) NOT NULL,
    `category_id` INT UNSIGNED NOT NULL,
    `description` TEXT NULL,
    `serial_number` VARCHAR(100) NULL,
    `purchase_date` DATE NULL,
    `purchase_price` DECIMAL(15, 2) NULL,
    `warranty_expiry` DATE NULL,
    `location` VARCHAR(200) NULL,
    `status` ENUM('available', 'assigned', 'in_repair', 'retired', 'lost') DEFAULT 'available',
    `employee_id` INT UNSIGNED NULL,
    `assigned_at` DATETIME NULL,
    `notes` TEXT NULL,
    `is_archived` TINYINT(1) DEFAULT 0,
    `archived_at` DATETIME NULL,
    `archived_by` INT UNSIGNED NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_category` (`category_id`),
    INDEX `idx_status` (`status`),
    INDEX `idx_employee` (`employee_id`),
    INDEX `idx_archived` (`is_archived`),
    FOREIGN KEY (`category_id`) REFERENCES `asset_categories`(`id`) ON DELETE RESTRICT,
    FOREIGN KEY (`employee_id`) REFERENCES `employees`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`archived_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed default asset categories
INSERT INTO `asset_categories` (`name`, `slug`, `description`, `icon`, `sort_order`) VALUES
('Computers & IT', 'computers-it', 'Laptops, desktops, servers, peripherals', 'fa-laptop', 1),
('Office Equipment', 'office-equipment', 'Printers, scanners, furniture', 'fa-print', 2),
('Vehicles', 'vehicles', 'Company vehicles', 'fa-car', 3),
('Electronics', 'electronics', 'Phones, tablets, AV equipment', 'fa-mobile-alt', 4),
('Other', 'other', 'Miscellaneous assets', 'fa-box', 5);
