<?php

require_once __DIR__ . '/Model.php';

class Message extends Model {
    protected $table = 'messages';

    public function getRecentMessages($coupleId, $limit = 50, $beforeId = null) {
        $sql = "SELECT m.*, u.name as sender_name, u.profile_photo as sender_photo, 
                       rm.message as reply_message, ru.name as reply_sender_name
                FROM messages m
                JOIN users u ON m.sender_id = u.id
                LEFT JOIN messages rm ON m.reply_to_id = rm.id
                LEFT JOIN users ru ON rm.sender_id = ru.id
                WHERE m.couple_id = :couple_id";

        $params = ['couple_id' => $coupleId];

        if ($beforeId) {
            $sql .= " AND m.id < :before_id";
            $params['before_id'] = $beforeId;
        }

        $sql .= " ORDER BY m.id DESC LIMIT " . (int)$limit;

        $stmt = $this->db()->prepare($sql);
        $stmt->execute($params);
        $results = $stmt->fetchAll();

        foreach ($results as &$row) {
            if (!empty($row['deleted_at'])) {
                $row['message'] = 'Message deleted';
                if ($row['message_type'] === 'image') {
                    $row['message_type'] = 'text';
                }
            }
        }

        // Reverse to display chronologically (oldest to newest)
        return array_reverse($results);
    }

    public function getNewMessages($coupleId, $afterId) {
        $sql = "SELECT m.*, u.name as sender_name, u.profile_photo as sender_photo,
                       rm.message as reply_message, ru.name as reply_sender_name
                FROM messages m
                JOIN users u ON m.sender_id = u.id
                LEFT JOIN messages rm ON m.reply_to_id = rm.id
                LEFT JOIN users ru ON rm.sender_id = ru.id
                WHERE m.couple_id = :couple_id AND m.id > :after_id
                ORDER BY m.id ASC";

        $stmt = $this->db()->prepare($sql);
        $stmt->execute([
            'couple_id' => $coupleId,
            'after_id' => $afterId
        ]);
        $results = $stmt->fetchAll();

        foreach ($results as &$row) {
            if (!empty($row['deleted_at'])) {
                $row['message'] = 'Message deleted';
                if ($row['message_type'] === 'image') {
                    $row['message_type'] = 'text';
                }
            }
        }
        
        return $results;
    }

    public function markAsRead($coupleId, $userId) {
        $stmt = $this->db()->prepare("UPDATE messages SET is_read = 1 WHERE couple_id = :couple_id AND sender_id != :user_id AND is_read = 0");
        return $stmt->execute([
            'couple_id' => $coupleId,
            'user_id' => $userId
        ]);
    }

    public function getAdminMessages($coupleId, $search = null, $senderId = null, $dateFrom = null, $dateTo = null, $limit = 100) {
        $sql = "SELECT m.*, u.name as sender_name, u.email as sender_email
                FROM messages m
                JOIN users u ON m.sender_id = u.id
                WHERE m.couple_id = :couple_id";

        $params = ['couple_id' => $coupleId];

        if ($search) {
            $sql .= " AND m.message LIKE :search";
            $params['search'] = "%{$search}%";
        }

        if ($senderId) {
            $sql .= " AND m.sender_id = :sender_id";
            $params['sender_id'] = $senderId;
        }

        if ($dateFrom) {
            $sql .= " AND m.created_at >= :date_from";
            $params['date_from'] = $dateFrom . " 00:00:00";
        }

        if ($dateTo) {
            $sql .= " AND m.created_at <= :date_to";
            $params['date_to'] = $dateTo . " 23:59:59";
        }

        $sql .= " ORDER BY m.created_at DESC LIMIT " . (int)$limit;

        $stmt = $this->db()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function softDelete($id) {
        $stmt = $this->db()->prepare("UPDATE messages SET deleted_at = NOW() WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
