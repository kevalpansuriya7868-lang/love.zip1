<?php

require_once __DIR__ . '/Model.php';

class TimelineEvent extends Model {
    protected $table = 'timeline_events';

    public function getCoupleEvents($coupleId) {
        $sql = "SELECT t.*, u.name as creator_name
                FROM timeline_events t
                JOIN users u ON t.created_by = u.id
                WHERE t.couple_id = :couple_id
                ORDER BY t.event_date ASC";

        $stmt = $this->db()->prepare($sql);
        $stmt->execute(['couple_id' => $coupleId]);
        return $stmt->fetchAll();
    }
}
