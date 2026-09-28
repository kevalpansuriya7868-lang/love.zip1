<?php

require_once __DIR__ . '/../Middleware/AuthMiddleware.php';
require_once __DIR__ . '/../Models/Notification.php';

class NotificationController {

    public function index() {
        AuthMiddleware::handle();

        $userId = $_SESSION['user']['id'];
        $notifModel = new Notification();

        $notifications = $notifModel->getUserNotifications($userId, 50);

        view('notifications/index', [
            'notifications' => $notifications,
            'pageTitle'     => 'Notifications'
        ]);

        unset($_SESSION['flash_error'], $_SESSION['flash_success']);
    }
}
