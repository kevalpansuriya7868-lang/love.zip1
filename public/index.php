<?php

session_start();

// Autoload helpers
require_once __DIR__ . '/../app/Helpers/functions.php';
require_once __DIR__ . '/../app/Helpers/Security.php';

// Autoload base classes
require_once __DIR__ . '/../app/Models/Model.php';
require_once __DIR__ . '/../app/Middleware/AuthMiddleware.php';
require_once __DIR__ . '/../app/Middleware/AdminMiddleware.php';
require_once __DIR__ . '/../app/Middleware/CsrfMiddleware.php';
require_once __DIR__ . '/../app/Services/UploadService.php';

// Autoload all Models
foreach (glob(__DIR__ . '/../app/Models/*.php') as $filename) {
    require_once $filename;
}

// Autoload Controllers
foreach (glob(__DIR__ . '/../app/Controllers/*.php') as $filename) {
    require_once $filename;
}
foreach (glob(__DIR__ . '/../app/Controllers/Admin/*.php') as $filename) {
    require_once $filename;
}
foreach (glob(__DIR__ . '/../app/Controllers/Api/*.php') as $filename) {
    require_once $filename;
}

// Load Routes
$webRoutes = require __DIR__ . '/../routes/web.php';
$apiRoutes = require __DIR__ . '/../routes/api.php';
$routes    = array_merge($webRoutes, $apiRoutes);

// Parse Request
$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    // Normalize subfolder path if running in subdirectory like /love/public
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    $basePath = preg_replace('#/public$#', '', $scriptDir);
    if ($basePath !== '/' && $basePath !== '') {
        if (strpos($requestUri, $basePath) === 0) {
            $requestUri = substr($requestUri, strlen($basePath));
        }
    }
    if (strpos($requestUri, '/public') === 0) {
        $requestUri = substr($requestUri, strlen('/public'));
    }
    $requestUri = '/' . trim($requestUri, '/');
    if (empty($requestUri)) {
        $requestUri = '/';
    }

$routeKey = "{$requestMethod} {$requestUri}";

if (isset($routes[$routeKey])) {
    $handler = $routes[$routeKey];
    $controllerName = $handler[0];
    $actionName = $handler[1];

    if (class_exists($controllerName)) {
        $controller = new $controllerName();
        if (method_exists($controller, $actionName)) {
            // Apply CSRF check for POST requests
            if ($requestMethod === 'POST') {
                CsrfMiddleware::handle();
            }
            $controller->$actionName();
            exit;
        }
    }
}

// 404 Handler
http_response_code(404);
if (strpos($requestUri, '/api/') === 0) {
    json_response(['success' => false, 'message' => 'API Endpoint Not Found'], 404);
} else {
    view('errors/404', ['pageTitle' => '404 - Page Not Found'], null);
}
