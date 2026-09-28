<?php

require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Models/Couple.php';
require_once __DIR__ . '/../Models/Admin.php';
require_once __DIR__ . '/../Helpers/Security.php';

class AuthController {

    public function showLogin() {
        if (is_logged_in()) {
            redirect('dashboard');
        }
        view('auth/login', [], null);
        unset($_SESSION['flash_error'], $_SESSION['flash_success']);
    }

    public function login() {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $_SESSION['flash_error'] = 'Please fill in both email and password.';
            redirect('login');
        }

        if (Security::isRateLimited('login_' . $email, 5, 15)) {
            $_SESSION['flash_error'] = 'Too many failed login attempts. Please try again in 15 minutes.';
            redirect('login');
        }

        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            Security::hitRateLimit('login_' . $email);
            $_SESSION['flash_error'] = 'Invalid email address or password.';
            redirect('login');
        }

        if ($user['status'] !== 'active') {
            $_SESSION['flash_error'] = 'Your account is currently disabled.';
            redirect('login');
        }

        $coupleModel = new Couple();
        $couple = $coupleModel->find($user['couple_id']);

        if (!$couple || $couple['status'] !== 'active') {
            $_SESSION['flash_error'] = 'Your couple space is disabled. Please contact the administrator.';
            redirect('login');
        }

        Security::clearRateLimit('login_' . $email);
        session_regenerate_id(true);

        $_SESSION['user'] = [
            'id'            => $user['id'],
            'couple_id'     => $user['couple_id'],
            'name'          => $user['name'],
            'email'         => $user['email'],
            'role'          => $user['role'],
            'profile_photo' => $user['profile_photo']
        ];

        $_SESSION['couple'] = [
            'id'                      => $couple['id'],
            'name'                    => $couple['name'],
            'slug'                    => $couple['slug'],
            'relationship_start_date' => $couple['relationship_start_date'],
            'profile_photo'           => $couple['profile_photo'],
            'bio'                     => $couple['bio']
        ];

        $userModel->updateLastSeen($user['id']);
        log_activity('user_login', "User {$user['name']} logged in.", null, $user['id'], $couple['id']);

        redirect('dashboard');
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['user'])) {
            log_activity('user_logout', "User {$_SESSION['user']['name']} logged out.");
        }
        unset($_SESSION['user'], $_SESSION['couple']);
        $_SESSION['flash_success'] = 'You have been logged out safely.';
        redirect('login');
    }

    public function showAdminLogin() {
        if (is_admin_logged_in()) {
            redirect('admin/dashboard');
        }
        view('auth/admin_login', [], null);
        unset($_SESSION['flash_error'], $_SESSION['flash_success']);
    }

    public function adminLogin() {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $_SESSION['flash_error'] = 'Please fill in all credentials.';
            redirect('admin/login');
        }

        if (Security::isRateLimited('admin_login_' . $email, 5, 15)) {
            $_SESSION['flash_error'] = 'Too many failed admin attempts. Try again in 15 minutes.';
            redirect('admin/login');
        }

        $adminModel = new Admin();
        $admin = $adminModel->findByEmail($email);

        if (!$admin || !password_verify($password, $admin['password'])) {
            Security::hitRateLimit('admin_login_' . $email);
            $_SESSION['flash_error'] = 'Invalid administrator credentials.';
            redirect('admin/login');
        }

        Security::clearRateLimit('admin_login_' . $email);
        session_regenerate_id(true);

        $_SESSION['admin'] = [
            'id'    => $admin['id'],
            'name'  => $admin['name'],
            'email' => $admin['email']
        ];

        $adminModel->updateLastLogin($admin['id']);
        log_activity('admin_login', "Admin {$admin['name']} logged in.", $admin['id']);

        redirect('admin/dashboard');
    }

    public function adminLogout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['admin'])) {
            log_activity('admin_logout', "Admin {$_SESSION['admin']['name']} logged out.");
        }
        unset($_SESSION['admin']);
        $_SESSION['flash_success'] = 'Admin logged out successfully.';
        redirect('admin/login');
    }

    public function showForgotPassword() {
        view('auth/forgot_password', [], null);
        unset($_SESSION['flash_error'], $_SESSION['flash_success']);
    }

    public function forgotPassword() {
        $email = trim($_POST['email'] ?? '');
        $_SESSION['flash_success'] = 'If an account exists for ' . e($email) . ', password reset instructions have been sent.';
        redirect('forgot-password');
    }
}
