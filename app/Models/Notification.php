<?php

require_once __DIR__ . '/Model.php';

class Notification extends Model {
    protected $table = 'notifications';

    public function getUserNotifications($userId, $limit = 20) {
        $sql = "SELECT * FROM notifications WHERE user_id = :user_id ORDER BY created_at DESC LIMIT " . (int)$limit;
        $stmt = $this->db()->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public function getUnreadCount($userId) {
        $stmt = $this->db()->prepare("SELECT COUNT(*) as cnt FROM notifications WHERE user_id = :user_id AND is_read = 0");
        $stmt->execute(['user_id' => $userId]);
        $res = $stmt->fetch();
        return (int)($res['cnt'] ?? 0);
    }

    public function markAllRead($userId) {
        $stmt = $this->db()->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = :user_id");
        return $stmt->execute(['user_id' => $userId]);
    }
}
