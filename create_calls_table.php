<?php
$config = require_once __DIR__ . '/config/database.php';

try {
    $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']};charset={$config['charset']}";
    $db = new PDO($dsn, $config['username'], $config['password'], $config['options']);
    
    // Create calls table for WebRTC signaling
    $db->exec("CREATE TABLE IF NOT EXISTS `calls` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `couple_id` INT NOT NULL,
        `caller_id` INT NOT NULL,
        `receiver_id` INT NOT NULL,
        `type` ENUM('voice', 'video') NOT NULL,
        `status` ENUM('initiating', 'ringing', 'answered', 'rejected', 'ended', 'missed') NOT NULL DEFAULT 'initiating',
        `offer` TEXT NULL,
        `answer` TEXT NULL,
        `caller_candidates` TEXT NULL,
        `receiver_candidates` TEXT NULL,
        `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
        `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (`couple_id`) REFERENCES `couples` (`id`) ON DELETE CASCADE,
        FOREIGN KEY (`caller_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
        FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
    
    echo "Created calls table successfully.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
