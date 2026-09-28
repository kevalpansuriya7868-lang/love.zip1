<?php

require_once __DIR__ . '/Model.php';

class Admin extends Model {
    protected $table = 'admins';

    public function findByEmail($email) {
        $stmt = $this->db()->prepare("SELECT * FROM admins WHERE email = :email OR name = :name LIMIT 1");
        $stmt->execute(['email' => $email, 'name' => $email]);
        return $stmt->fetch();
    }

    public function updateLastLogin($adminId) {
        $stmt = $this->db()->prepare("UPDATE admins SET last_login = NOW() WHERE id = :id");
        return $stmt->execute(['id' => $adminId]);
    }
}
