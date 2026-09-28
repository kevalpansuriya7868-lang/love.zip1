<?php

require_once __DIR__ . '/../../Models/Notification.php';

class NotificationApiController {

    private function checkAuth() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!is_logged_in()) {
            json_response(['success' => false, 'message' => 'Unauthorized access'], 401);
        }
    }

    public function getUnread() {
        $this->checkAuth();

        $userId = $_SESSION['user']['id'];
        $notifModel = new Notification();

        $count = $notifModel->getUnreadCount($userId);
        $recent = $notifModel->getUserNotifications($userId, 5);

        json_response([
            'success'      => true,
            'unread_count' => $count,
            'data'         => $recent
        ]);
    }

    public function markAllRead() {
        $this->checkAuth();

        $userId = $_SESSION['user']['id'];
        $notifModel = new Notification();
        $notifModel->markAllRead($userId);

        json_response([
            'success' => true,
            'message' => 'Notifications marked as read.'
        ]);
    }
}
