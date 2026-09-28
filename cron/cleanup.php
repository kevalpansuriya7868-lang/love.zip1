<?php
// Automatic Disappearing / Expired Messages Cleanup Script
// Usage: php cron/cleanup.php

require_once __DIR__ . '/../app/Helpers/functions.php';
require_once __DIR__ . '/../app/Models/Model.php';
require_once __DIR__ . '/../app/Models/Setting.php';
require_once __DIR__ . '/../app/Models/Message.php';

echo "[DEBUG] Starting scheduled message retention cleanup...\n";

$retention = get_setting('message_retention', 'never');
if ($retention === 'never') {
    echo "[INFO] Message retention policy set to 'never'. Skipping cleanup.\n";
    exit(0);
}

$days = 0;
switch ($retention) {
    case '24h':
        $days = 1;
        break;
    case '7d':
        $days = 7;
        break;
    case '30d':
        $days = 30;
        break;
    case 'custom':
        $days = (int)get_setting('message_retention_days', 30);
        break;
    default:
        $days = 0;
}

if ($days <= 0) {
    echo "[INFO] Invalid retention period. Skipping.\n";
    exit(0);
}

$cutoffDate = date('Y-m-d H:i:s', strtotime("-{$days} days"));
echo "[INFO] Deleting messages created before {$cutoffDate} (Retention: {$days} days)...\n";

try {
    $stmt = db()->prepare("DELETE FROM messages WHERE created_at < :cutoff");
    $stmt->execute(['cutoff' => $cutoffDate]);
    $count = $stmt->rowCount();
    echo "[SUCCESS] Permanently cleaned up {$count} expired message(s).\n";

    log_activity('cron_cleanup', "Cleaned up {$count} messages older than {$days} days.");
} catch (Exception $e) {
    echo "[ERROR] Cleanup failed: " . $e->getMessage() . "\n";
    exit(1);
}
