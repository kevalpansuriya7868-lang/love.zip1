<?php

require_once __DIR__ . '/Model.php';

class User extends Model {
    protected $table = 'users';

    public function findByEmail($email) {
        $stmt = $this->db()->prepare("SELECT * FROM users WHERE email = :email OR name = :name LIMIT 1");
        $stmt->execute(['email' => $email, 'name' => $email]);
        return $stmt->fetch();
    }

    public function updateLastSeen($userId) {
        $stmt = $this->db()->prepare("UPDATE users SET last_seen = NOW() WHERE id = :id");
        return $stmt->execute(['id' => $userId]);
    }
}
