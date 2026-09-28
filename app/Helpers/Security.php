<?php

class Security {

    public static function isRateLimited($key, $maxAttempts = 5, $decayMinutes = 15) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $attemptsKey = "rate_limit_" . md5($key);
        $timeKey = "rate_limit_time_" . md5($key);

        $now = time();
        if (isset($_SESSION[$timeKey]) && ($now - $_SESSION[$timeKey]) > ($decayMinutes * 60)) {
            unset($_SESSION[$attemptsKey], $_SESSION[$timeKey]);
        }

        $attempts = $_SESSION[$attemptsKey] ?? 0;
        return $attempts >= $maxAttempts;
    }

    public static function hitRateLimit($key) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $attemptsKey = "rate_limit_" . md5($key);
        $timeKey = "rate_limit_time_" . md5($key);

        if (!isset($_SESSION[$timeKey])) {
            $_SESSION[$timeKey] = time();
        }
        $_SESSION[$attemptsKey] = ($_SESSION[$attemptsKey] ?? 0) + 1;
    }

    public static function clearRateLimit($key) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $attemptsKey = "rate_limit_" . md5($key);
        $timeKey = "rate_limit_time_" . md5($key);
        unset($_SESSION[$attemptsKey], $_SESSION[$timeKey]);
    }

    public static function validateImageUpload($file, $maxSizeBytes = 10485760) {
        if (!isset($file['error']) || is_array($file['error'])) {
            return ['valid' => false, 'error' => 'Invalid upload payload.'];
        }

        switch ($file['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                return ['valid' => false, 'error' => 'File size exceeds maximum limit.'];
            case UPLOAD_ERR_NO_FILE:
                return ['valid' => false, 'error' => 'No file uploaded.'];
            default:
                return ['valid' => false, 'error' => 'Upload error occurred.'];
        }

        if ($file['size'] > $maxSizeBytes) {
            return ['valid' => false, 'error' => 'File size is too large (max ' . round($maxSizeBytes / 1024 / 1024) . 'MB).'];
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);

        $allowedMimes = [
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp',
            'image/gif'  => 'gif'
        ];

        if (!array_key_exists($mime, $allowedMimes)) {
            return ['valid' => false, 'error' => 'Invalid file format. Only JPG, PNG, WEBP and GIF images are allowed.'];
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
            return ['valid' => false, 'error' => 'Invalid file extension.'];
        }

        return ['valid' => true, 'ext' => $allowedMimes[$mime]];
    }

    public static function generateSafeFilename($ext) {
        return bin2hex(random_bytes(16)) . '_' . time() . '.' . $ext;
    }
}
