<?php

class CsrfMiddleware {
    public static function handle() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
            if (!verify_csrf_token($token)) {
                if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                    json_response(['success' => false, 'message' => 'CSRF verification failed.'], 403);
                } else {
                    die('403 Forbidden - Invalid CSRF Token');
                }
            }
        }
    }
}
