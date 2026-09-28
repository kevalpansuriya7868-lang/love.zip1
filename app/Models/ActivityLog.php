<?php

require_once __DIR__ . '/Model.php';

class ActivityLog extends Model {
    protected $table = 'activity_logs';

    public function getRecentLogs($limit = 100) {
        $sql = "SELECT a.*, 
                       adm.name as admin_name, 
                       usr.name as user_name, 
                       c.name as couple_name
                FROM activity_logs a
                LEFT JOIN admins adm ON a.admin_id = adm.id
                LEFT JOIN users usr ON a.user_id = usr.id
                LEFT JOIN couples c ON a.couple_id = c.id
                ORDER BY a.created_at DESC LIMIT " . (int)$limit;

        $stmt = $this->db()->query($sql);
        return $stmt->fetchAll();
    }
}
