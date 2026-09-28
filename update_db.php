<?php
$config = require_once __DIR__ . '/config/database.php';

try {
    $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']};charset={$config['charset']}";
    $db = new PDO($dsn, $config['username'], $config['password'], $config['options']);
    
    // Create photo_folders table
    $db->exec("CREATE TABLE IF NOT EXISTS `photo_folders` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `couple_id` INT NOT NULL,
        `name` VARCHAR(150) NOT NULL,
        `created_by` INT NOT NULL,
        `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (`couple_id`) REFERENCES `couples` (`id`) ON DELETE CASCADE,
        FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
    echo "Created photo_folders table.\n";

    // Add folder_id to photos table
    try {
        $db->exec("ALTER TABLE `photos` ADD `folder_id` INT NULL DEFAULT NULL AFTER `couple_id`;");
        $db->exec("ALTER TABLE `photos` ADD FOREIGN KEY (`folder_id`) REFERENCES `photo_folders` (`id`) ON DELETE SET NULL;");
        echo "Added folder_id to photos table.\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
            echo "Column folder_id already exists in photos table.\n";
        } else {
            throw $e;
        }
    }
    
    echo "Database update completed successfully.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
