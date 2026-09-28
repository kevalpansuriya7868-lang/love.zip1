<?php

require_once __DIR__ . '/../Middleware/AuthMiddleware.php';
require_once __DIR__ . '/../Models/Couple.php';
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Models/Message.php';

class ChatController {

    public function index() {
        AuthMiddleware::handle();

        $coupleId = $_SESSION['couple']['id'];
        $userId   = $_SESSION['user']['id'];

        $coupleModel  = new Couple();
        $messageModel = new Message();

        $partner = $coupleModel->getPartnerOf($coupleId, $userId);
        $messages = $messageModel->getRecentMessages($coupleId, 50);

        // Mark messages as read
        $messageModel->markAsRead($coupleId, $userId);

        view('chat/index', [
            'partner'   => $partner,
            'messages'  => $messages,
            'pageTitle' => 'Private Chat — ' . e($_SESSION['couple']['name'])
        ]);

        unset($_SESSION['flash_error'], $_SESSION['flash_success']);
    }
}
