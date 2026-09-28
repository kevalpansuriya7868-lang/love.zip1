<?php

require_once __DIR__ . '/Model.php';

class Photo extends Model {
    protected $table = 'photos';

    public function getCouplePhotos($coupleId, $limit = 50, $offset = 0, $folderId = false) {
        $sql = "SELECT p.*, u.name as uploader_name
                FROM photos p
                JOIN users u ON p.uploaded_by = u.id
                WHERE p.couple_id = :couple_id";
        
        $params = ['couple_id' => $coupleId];

        if ($folderId === null) {
            $sql .= " AND p.folder_id IS NULL";
        } elseif ($folderId !== false) {
            $sql .= " AND p.folder_id = :folder_id";
            $params['folder_id'] = $folderId;
        }

        $sql .= " ORDER BY p.uploaded_at DESC LIMIT " . (int)$limit . " OFFSET " . (int)$offset;

        $stmt = $this->db()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getAllPhotosAdmin($coupleId = null, $search = null, $limit = 60) {
        $sql = "SELECT p.*, u.name as uploader_name, c.name as couple_name
                FROM photos p
                JOIN users u ON p.uploaded_by = u.id
                JOIN couples c ON p.couple_id = c.id
                WHERE 1=1";

        $params = [];

        if ($coupleId) {
            $sql .= " AND p.couple_id = :couple_id";
            $params['couple_id'] = $coupleId;
        }

        if ($search) {
            $sql .= " AND (p.caption LIKE :search OR p.filename LIKE :search)";
            $params['search'] = "%{$search}%";
        }

        $sql .= " ORDER BY p.uploaded_at DESC LIMIT " . (int)$limit;

        $stmt = $this->db()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}
