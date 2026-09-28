<?php
// Database Seeder Script
// Usage: php database/seeder.php

require_once __DIR__ . '/../app/Helpers/functions.php';

echo "[DEBUG] Executing Database Seeder for Couples Private Website...\n";

$db = db();

// 1. Run Schema SQL
$schemaFile = __DIR__ . '/schema.sql';
if (file_exists($schemaFile)) {
    $sql = file_get_contents($schemaFile);
    $db->exec($sql);
    echo "[SUCCESS] Database schema initialized.\n";
}

// 2. Clear Existing Data
$db->exec("SET FOREIGN_KEY_CHECKS = 0;");
$tables = ['admins', 'couples', 'users', 'messages', 'photos', 'memories', 'memory_photos', 'timeline_events', 'notifications', 'settings', 'activity_logs'];
foreach ($tables as $table) {
    $db->exec("TRUNCATE TABLE `{$table}`");
}
$db->exec("SET FOREIGN_KEY_CHECKS = 1;");

// 3. Seed Admin: username/email "parth 309", password "i love dhamu"
$adminPassword = password_hash('i love dhamu', PASSWORD_DEFAULT);
$stmt = $db->prepare("INSERT INTO admins (name, email, password, created_at) VALUES (:name, :email, :pass, NOW())");
$stmt->execute([
    'name'  => 'parth 309',
    'email' => 'parth 309',
    'pass'  => $adminPassword
]);
$adminId = $db->lastInsertId();
echo "[SUCCESS] Admin created: parth 309 / i love dhamu\n";

// 4. Seed Couple 1: i am lier
$stmt = $db->prepare("INSERT INTO couples (name, slug, relationship_start_date, bio, status, created_at) VALUES (:name, :slug, :date, :bio, 'active', NOW())");
$stmt->execute([
    'name' => 'Dharth',
    'slug' => 'dharth',
    'date' => '2024-02-16',
    'bio'  => 'Writing our sweet journey together one moment at a time. ❤️'
]);
$couple1Id = $db->lastInsertId();

// Partner 1: username/email "dhamu 309", password "dharth 309"
$puser1Password = password_hash('dharth 309', PASSWORD_DEFAULT);
$stmt = $db->prepare("INSERT INTO users (couple_id, name, email, password, role, birthday, status, created_at) VALUES (:cid, :name, :email, :pass, 'partner_1', '1998-05-20', 'active', NOW())");
$stmt->execute([
    'cid'   => $couple1Id,
    'name'  => 'i am lier',
    'email' => 'dhamu 309',
    'pass'  => $puser1Password
]);
$emmaId = $db->lastInsertId();

// Partner 2: username/email "user2", password "puser1"
$stmt->execute([
    'cid'   => $couple1Id,
    'name'  => 'Parth',
    'email' => 'user2',
    'pass'  => $puser1Password
]);
$noahId = $db->lastInsertId();

echo "[SUCCESS] Couple 1 created: Dhamu (dhamu 309 / dharth 309), Parth (user2 / dharth 309)\n";

// 5. Seed Couple 2: Sophia & Liam
$stmt = $db->prepare("INSERT INTO couples (name, slug, relationship_start_date, bio, status, created_at) VALUES (:name, :slug, :date, :bio, 'active', NOW())");
$stmt->execute([
    'name' => 'Sophia & Liam',
    'slug' => 'sophia-liam',
    'date' => '2023-08-10',
    'bio'  => 'Adventures, laughs, and endless love.'
]);
$couple2Id = $db->lastInsertId();

$stmt = $db->prepare("INSERT INTO users (couple_id, name, email, password, role, birthday, status, created_at) VALUES (:cid, :name, :email, :pass, 'partner_1', '1996-11-04', 'active', NOW())");
$stmt->execute([
    'cid'   => $couple2Id,
    'name'  => 'Sophia Miller',
    'email' => 'sophia',
    'pass'  => $puser1Password
]);
$sophiaId = $db->lastInsertId();

$stmt->execute([
    'cid'   => $couple2Id,
    'name'  => 'Liam Davis',
    'email' => 'liam',
    'pass'  => $puser1Password
]);
$liamId = $db->lastInsertId();

echo "[SUCCESS] Couple 2 created: Sophia (sophia / puser1), Liam (liam / puser1)\n";

// 6. Seed Demo Messages
$msgStmt = $db->prepare("INSERT INTO messages (couple_id, sender_id, message, message_type, is_read, created_at) VALUES (:cid, :sid, :msg, 'text', 1, :cat)");
$msgStmt->execute(['cid' => $couple1Id, 'sid' => $emmaId, 'msg' => 'Good morning my love! ❤️ Did you sleep well?', 'cat' => date('Y-m-d H:i:s', strtotime('-2 hours'))]);
$msgStmt->execute(['cid' => $couple1Id, 'sid' => $noahId, 'msg' => 'Morning darling! Yes, dreamed of our upcoming trip ✈️', 'cat' => date('Y-m-d H:i:s', strtotime('-1 hour 50 mins'))]);

// 7. Seed Demo Timeline Events
$timeStmt = $db->prepare("INSERT INTO timeline_events (couple_id, title, description, event_date, icon, created_by, created_at) VALUES (:cid, :title, :desc, :edate, :icon, :cb, NOW())");
$timeStmt->execute([
    'cid'   => $couple1Id,
    'title' => 'We First Met',
    'desc'  => 'Crossed paths at the cozy coffee shop downtown on Valentine’s Day.',
    'edate' => '2024-02-07',
    'icon'  => 'fa-heart',
    'cb'    => $emmaId
]);

// 8. Seed Demo Memories
$memStmt = $db->prepare("INSERT INTO memories (couple_id, title, description, memory_date, location, created_by, created_at) VALUES (:cid, :title, :desc, :mdate, :loc, :cb, NOW())");
$memStmt->execute([
    'cid'   => $couple1Id,
    'title' => 'Sunset Beach Walk',
    'desc'  => 'Watched the sun go down with warm ocean breeze and iced coffees in hand.',
    'mdate' => '2024-07-22',
    'loc'   => 'Malibu Coast',
    'cb'    => $emmaId
]);

// 9. Seed Default Settings
$settings = [
    'site_name'                 => 'Couples Private Space',
    'timezone'                  => 'UTC',
    'maintenance_mode'          => 'disabled',
    'message_retention'         => 'never',
    'allow_user_message_delete' => '1',
    'allow_image_messages'      => '1',
    'typing_indicator'          => '1',
    'max_file_size_mb'          => '10',
    'allowed_file_types'        => 'jpg,jpeg,png,webp',
    'login_max_attempts'        => '5',
    'session_timeout_minutes'   => '120'
];

$setStmt = $db->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (:key, :val)");
foreach ($settings as $k => $v) {
    $setStmt->execute(['key' => $k, 'val' => $v]);
}

echo "[SUCCESS] Default system settings seeded.\n";
echo "[FINISHED] Seeding complete! Demo environment ready.\n";
