<?php

require_once __DIR__ . '/Model.php';

class PhotoFolder extends Model {
    protected $table = 'photo_folders';

    public function getCoupleFolders($coupleId) {
        $sql = "SELECT pf.*, u.name as creator_name,
                       (SELECT COUNT(*) FROM photos p WHERE p.folder_id = pf.id) as photo_count
                FROM photo_folders pf
                JOIN users u ON pf.created_by = u.id
                WHERE pf.couple_id = :couple_id
                ORDER BY pf.created_at DESC";

        $stmt = $this->db()->prepare($sql);
        $stmt->execute(['couple_id' => $coupleId]);
        return $stmt->fetchAll();
    }
    
    public function getFolder($id, $coupleId) {
        $sql = "SELECT * FROM photo_folders WHERE id = :id AND couple_id = :couple_id LIMIT 1";
        $stmt = $this->db()->prepare($sql);
        $stmt->execute(['id' => $id, 'couple_id' => $coupleId]);
        return $stmt->fetch();
    }
}
