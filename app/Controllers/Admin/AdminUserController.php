<?php

require_once __DIR__ . '/../../Middleware/AdminMiddleware.php';
require_once __DIR__ . '/../../Models/User.php';
require_once __DIR__ . '/../../Models/Couple.php';

class AdminUserController {

    public function index() {
        AdminMiddleware::handle();

        $userModel   = new User();
        $coupleModel = new Couple();

        $search   = trim($_GET['search'] ?? '');
        $coupleId = !empty($_GET['couple_id']) ? (int)$_GET['couple_id'] : null;

        $sql = "SELECT u.*, c.name as couple_name FROM users u JOIN couples c ON u.couple_id = c.id WHERE 1=1";
        $params = [];

        if ($search) {
            $sql .= " AND (u.name LIKE :search OR u.email LIKE :search)";
            $params['search'] = "%{$search}%";
        }

        if ($coupleId) {
            $sql .= " AND u.couple_id = :couple_id";
            $params['couple_id'] = $coupleId;
        }

        $sql .= " ORDER BY u.id DESC";

        $stmt = db()->prepare($sql);
        $stmt->execute($params);
        $users = $stmt->fetchAll();

        $couples = $coupleModel->all('name ASC');

        view('admin/users/index', [
            'users'     => $users,
            'couples'   => $couples,
            'search'    => $search,
            'coupleId'  => $coupleId,
            'pageTitle' => 'User Management'
        ], 'layouts/admin_header');

        unset($_SESSION['flash_error'], $_SESSION['flash_success']);
    }

    public function toggleStatus() {
        AdminMiddleware::handle();

        $userId = (int)($_POST['id'] ?? 0);
        $userModel = new User();
        $user = $userModel->find($userId);

        if ($user) {
            $newStatus = $user['status'] === 'active' ? 'disabled' : 'active';
            $userModel->update($userId, ['status' => $newStatus]);
            log_activity('admin_toggle_user', "Changed user {$user['name']} status to {$newStatus}", $_SESSION['admin']['id'], $userId, $user['couple_id']);
            $_SESSION['flash_success'] = "User {$user['name']} is now {$newStatus}.";
        }

        redirect('admin/users');
    }

    public function resetPassword() {
        AdminMiddleware::handle();

        $userId = (int)($_POST['id'] ?? 0);
        $newPassword = $_POST['new_password'] ?? '';

        if (empty($newPassword) || strlen($newPassword) < 6) {
            $_SESSION['flash_error'] = 'Password must be at least 6 characters.';
            redirect('admin/users');
        }

        $userModel = new User();
        $user = $userModel->find($userId);

        if ($user) {
            $userModel->update($userId, ['password' => password_hash($newPassword, PASSWORD_DEFAULT)]);
            log_activity('admin_reset_user_password', "Reset password for user {$user['name']}", $_SESSION['admin']['id'], $userId, $user['couple_id']);
            $_SESSION['flash_success'] = "Password for {$user['name']} reset successfully.";
        }

        redirect('admin/users');
    }
}
