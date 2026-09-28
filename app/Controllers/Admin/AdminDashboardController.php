<?php

require_once __DIR__ . '/../../Middleware/AdminMiddleware.php';
require_once __DIR__ . '/../../Models/Couple.php';
require_once __DIR__ . '/../../Models/User.php';
require_once __DIR__ . '/../../Models/Message.php';
require_once __DIR__ . '/../../Models/Photo.php';
require_once __DIR__ . '/../../Models/Memory.php';
require_once __DIR__ . '/../../Models/ActivityLog.php';

class AdminDashboardController {

    public function index() {
        AdminMiddleware::handle();

        $coupleModel   = new Couple();
        $userModel     = new User();
        $messageModel  = new Message();
        $photoModel    = new Photo();
        $memoryModel   = new Memory();
        $logModel      = new ActivityLog();

        $stats = [
            'total_couples'  => $coupleModel->count(),
            'active_couples' => $coupleModel->count("status = 'active'"),
            'total_users'    => $userModel->count(),
            'total_messages' => $messageModel->count(),
            'messages_today' => $messageModel->count("DATE(created_at) = CURDATE()"),
            'total_photos'   => $photoModel->count(),
            'total_memories' => $memoryModel->count()
        ];

        $recentCouples = $coupleModel->all('id DESC LIMIT 5');
        foreach ($recentCouples as &$c) {
            $c['partner_1'] = $coupleModel->getPartnerOne($c['id']);
            $c['partner_2'] = $coupleModel->getPartnerTwo($c['id']);
        }

        $recentLogs = $logModel->getRecentLogs(10);

        view('admin/dashboard', [
            'stats'         => $stats,
            'recentCouples' => $recentCouples,
            'recentLogs'    => $recentLogs,
            'pageTitle'     => 'Admin Dashboard'
        ], 'layouts/admin_header');

        unset($_SESSION['flash_error'], $_SESSION['flash_success']);
    }
}
