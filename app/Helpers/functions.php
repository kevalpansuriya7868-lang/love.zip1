<?php

if (!function_exists('db')) {
    function db() {
        static $pdo = null;
        if ($pdo === null) {
            $config = require __DIR__ . '/../../config/database.php';
            $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']};charset={$config['charset']}";
            try {
                $pdo = new PDO($dsn, $config['username'], $config['password'], $config['options']);
            } catch (PDOException $e) {
                die("Database Connection Error: " . $e->getMessage());
            }
        }
        return $pdo;
    }
}

if (!function_exists('config')) {
    function config($key, $default = null) {
        static $configs = [];
        $parts = explode('.', $key);
        $file = $parts[0];
        if (!isset($configs[$file])) {
            $path = __DIR__ . "/../../config/{$file}.php";
            if (file_exists($path)) {
                $configs[$file] = require $path;
            } else {
                return $default;
            }
        }
        $val = $configs[$file];
        for ($i = 1; $i < count($parts); $i++) {
            if (is_array($val) && isset($val[$parts[$i]])) {
                $val = $val[$parts[$i]];
            } else {
                return $default;
            }
        }
        return $val;
    }
}

if (!function_exists('get_setting')) {
    function get_setting($key, $default = null) {
        try {
            $stmt = db()->prepare("SELECT setting_value FROM settings WHERE setting_key = :key LIMIT 1");
            $stmt->execute(['key' => $key]);
            $res = $stmt->fetch();
            return $res ? $res['setting_value'] : $default;
        } catch (Exception $e) {
            return $default;
        }
    }
}

if (!function_exists('url')) {
    function url($path = '') {
        $baseUrl = rtrim(config('app.url', 'http://localhost/love/public'), '/');
        $path = ltrim($path, '/');
        return $path ? "{$baseUrl}/{$path}" : $baseUrl;
    }
}

if (!function_exists('asset')) {
    function asset($path = '') {
        return url('assets/' . ltrim($path, '/'));
    }
}

if (!function_exists('upload_url')) {
    function upload_url($path = '') {
        if (!$path) return '';
        if (strpos($path, 'http://') === 0 || strpos($path, 'https://') === 0) {
            return $path;
        }
        return url('uploads/' . ltrim($path, '/'));
    }
}

if (!function_exists('redirect')) {
    function redirect($path) {
        $target = (strpos($path, 'http://') === 0 || strpos($path, 'https://') === 0) ? $path : url($path);
        header("Location: {$target}");
        exit;
    }
}

if (!function_exists('e')) {
    function e($value) {
        return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}

if (!function_exists('verify_csrf_token')) {
    function verify_csrf_token($token) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return !empty($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], (string)$token);
    }
}

if (!function_exists('json_response')) {
    function json_response($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
        exit;
    }
}

if (!function_exists('view')) {
    function view($viewName, $data = [], $layout = 'layouts/header') {
        extract($data);
        $viewFile = __DIR__ . "/../Views/{$viewName}.php";
        if (!file_exists($viewFile)) {
            die("View file not found: {$viewName}");
        }

        if ($layout) {
            $headerFile = __DIR__ . "/../Views/{$layout}.php";
            $footerFile = strpos($layout, 'admin') !== false ? __DIR__ . "/../Views/layouts/admin_footer.php" : __DIR__ . "/../Views/layouts/footer.php";
            if (file_exists($headerFile)) include $headerFile;
            include $viewFile;
            if (file_exists($footerFile)) include $footerFile;
        } else {
            include $viewFile;
        }
    }
}

if (!function_exists('auth_user')) {
    function auth_user() {
        return $_SESSION['user'] ?? null;
    }
}

if (!function_exists('auth_couple')) {
    function auth_couple() {
        return $_SESSION['couple'] ?? null;
    }
}

if (!function_exists('auth_admin')) {
    function auth_admin() {
        return $_SESSION['admin'] ?? null;
    }
}

if (!function_exists('is_logged_in')) {
    function is_logged_in() {
        return !empty($_SESSION['user']) && !empty($_SESSION['couple']);
    }
}

if (!function_exists('is_admin_logged_in')) {
    function is_admin_logged_in() {
        return !empty($_SESSION['admin']);
    }
}

if (!function_exists('format_relationship_duration')) {
    function format_relationship_duration($startDateStr) {
        if (!$startDateStr) return '0 days';
        $start = new DateTime($startDateStr);
        $now = new DateTime();
        $diff = $start->diff($now);

        $parts = [];
        if ($diff->y > 0) {
            $parts[] = $diff->y . ' ' . ($diff->y === 1 ? 'year' : 'years');
        }
        if ($diff->m > 0) {
            $parts[] = $diff->m . ' ' . ($diff->m === 1 ? 'month' : 'months');
        }
        if ($diff->d > 0 || empty($parts)) {
            $parts[] = $diff->d . ' ' . ($diff->d === 1 ? 'day' : 'days');
        }

        return implode(', ', $parts);
    }
}

if (!function_exists('time_ago')) {
    function time_ago($datetime) {
        if (!$datetime) return '';
        $timestamp = strtotime($datetime);
        $difference = time() - $timestamp;
        if ($difference < 60) return 'Just now';
        $periods = ["sec", "min", "hr", "day", "week", "month", "year", "decade"];
        $lengths = ["60", "60", "24", "7", "4.35", "12", "10"];
        for ($j = 0; $difference >= $lengths[$j] && $j < count($lengths) - 1; $j++) {
            $difference /= $lengths[$j];
        }
        $difference = round($difference);
        if ($j === 1) return "{$difference} min ago";
        if ($j === 2) return "{$difference} hr ago";
        if ($j === 3) return "{$difference} days ago";
        return date('M j, Y', $timestamp);
    }
}

if (!function_exists('log_activity')) {
    function log_activity($action, $description = null, $adminId = null, $userId = null, $coupleId = null) {
        try {
            $adminId = $adminId ?? ($_SESSION['admin']['id'] ?? null);
            $userId = $userId ?? ($_SESSION['user']['id'] ?? null);
            $coupleId = $coupleId ?? ($_SESSION['couple']['id'] ?? null);
            $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';

            $stmt = db()->prepare("INSERT INTO activity_logs (admin_id, user_id, couple_id, action, description, ip_address, created_at) VALUES (:admin_id, :user_id, :couple_id, :action, :description, :ip, NOW())");
            $stmt->execute([
                'admin_id'    => $adminId,
                'user_id'     => $userId,
                'couple_id'   => $coupleId,
                'action'      => $action,
                'description' => $description,
                'ip'          => $ip
            ]);
        } catch (Exception $e) {
            // Ignore logging errors silently
        }
    }
}
