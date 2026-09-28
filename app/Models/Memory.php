<?php

require_once __DIR__ . '/Model.php';

class Memory extends Model {
    protected $table = 'memories';

    public function getCoupleMemories($coupleId, $limit = 50) {
        $sql = "SELECT m.*, u.name as creator_name
                FROM memories m
                JOIN users u ON m.created_by = u.id
                WHERE m.couple_id = :couple_id
                ORDER BY m.memory_date DESC, m.id DESC LIMIT " . (int)$limit;

        $stmt = $this->db()->prepare($sql);
        $stmt->execute(['couple_id' => $coupleId]);
        return $stmt->fetchAll();
    }

    public function getMemoryPhotos($memoryId) {
        $stmt = $this->db()->prepare("SELECT * FROM memory_photos WHERE memory_id = :memory_id ORDER BY id ASC");
        $stmt->execute(['memory_id' => $memoryId]);
        return $stmt->fetchAll();
    }

    public function addMemoryPhoto($memoryId, $filePath) {
        $stmt = $this->db()->prepare("INSERT INTO memory_photos (memory_id, file_path, created_at) VALUES (:memory_id, :file_path, NOW())");
        return $stmt->execute([
            'memory_id' => $memoryId,
            'file_path' => $filePath
        ]);
    }

    public function deleteMemoryPhotos($memoryId) {
        $stmt = $this->db()->prepare("DELETE FROM memory_photos WHERE memory_id = :memory_id");
        return $stmt->execute(['memory_id' => $memoryId]);
    }
}
