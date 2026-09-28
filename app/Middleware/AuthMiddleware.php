<?php

class AuthMiddleware {
    public static function handle() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!is_logged_in()) {
            $_SESSION['flash_error'] = 'Please log in to access your couple space.';
            redirect('login');
        }

        // Check if couple status is active
        $coupleModel = new Couple();
        $couple = $coupleModel->find($_SESSION['couple']['id']);
        if (!$couple || $couple['status'] !== 'active') {
            unset($_SESSION['user'], $_SESSION['couple']);
            $_SESSION['flash_error'] = 'Your couple account is disabled. Please contact the administrator.';
            redirect('login');
        }

        // Check user status
        $userModel = new User();
        $user = $userModel->find($_SESSION['user']['id']);
        if (!$user || $user['status'] !== 'active') {
            unset($_SESSION['user'], $_SESSION['couple']);
            $_SESSION['flash_error'] = 'Your account has been disabled.';
            redirect('login');
        }

        // Update user last seen
        $userModel->updateLastSeen($user['id']);
    }

    public static function handleAjax() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!is_logged_in()) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Unauthorized']);
            exit;
        }
        
        $userModel = new User();
        $userModel->updateLastSeen($_SESSION['user']['id']);
    }
}
