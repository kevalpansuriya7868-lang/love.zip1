<?php

class AdminMiddleware {
    public static function handle() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!is_admin_logged_in()) {
            $_SESSION['flash_error'] = 'Administrator authentication required.';
            redirect('admin/login');
        }
    }
}
