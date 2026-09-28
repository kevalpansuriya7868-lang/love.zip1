<?php

require_once __DIR__ . '/Model.php';

class Couple extends Model {
    protected $table = 'couples';

    public function findBySlug($slug) {
        return $this->firstWhere('slug = :slug', ['slug' => $slug]);
    }

    public function getPartners($coupleId) {
        $stmt = $this->db()->prepare("SELECT * FROM users WHERE couple_id = :couple_id ORDER BY id ASC");
        $stmt->execute(['couple_id' => $coupleId]);
        return $stmt->fetchAll();
    }

    public function getPartnerOne($coupleId) {
        $partners = $this->getPartners($coupleId);
        return $partners[0] ?? null;
    }

    public function getPartnerTwo($coupleId) {
        $partners = $this->getPartners($coupleId);
        return $partners[1] ?? null;
    }

    public function getPartnerOf($coupleId, $userId) {
        $stmt = $this->db()->prepare("SELECT * FROM users WHERE couple_id = :couple_id AND id != :user_id LIMIT 1");
        $stmt->execute(['couple_id' => $coupleId, 'user_id' => $userId]);
        return $stmt->fetch();
    }
}
