<?php

require_once __DIR__ . '/../../Middleware/AdminMiddleware.php';
require_once __DIR__ . '/../../Models/ActivityLog.php';

class AdminActivityController {

    public function index() {
        AdminMiddleware::handle();

        $logModel = new ActivityLog();
        $logs = $logModel->getRecentLogs(150);

        view('admin/activity/index', [
            'logs'      => $logs,
            'pageTitle' => 'System Activity Logs'
        ], 'layouts/admin_header');

        unset($_SESSION['flash_error'], $_SESSION['flash_success']);
    }
}
