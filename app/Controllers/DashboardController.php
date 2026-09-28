<?php

require_once __DIR__ . '/../Middleware/AuthMiddleware.php';
require_once __DIR__ . '/../Models/Couple.php';
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Models/Message.php';
require_once __DIR__ . '/../Models/Photo.php';
require_once __DIR__ . '/../Models/Memory.php';
require_once __DIR__ . '/../Models/TimelineEvent.php';

class DashboardController {

    public function index() {
        AuthMiddleware::handle();

        $coupleId = $_SESSION['couple']['id'];
        $userId   = $_SESSION['user']['id'];

        $coupleModel   = new Couple();
        $userModel     = new User();
        $messageModel  = new Message();
        $photoModel    = new Photo();
        $memoryModel   = new Memory();
        $timelineModel = new TimelineEvent();

        $couple  = $coupleModel->find($coupleId);
        $partner = $coupleModel->getPartnerOf($coupleId, $userId);
        $me      = $userModel->find($userId);

        $recentMessages = $messageModel->getRecentMessages($coupleId, 5);
        $recentPhotos   = $photoModel->getCouplePhotos($coupleId, 6);
        $memories       = $memoryModel->getCoupleMemories($coupleId, 4);
        $timelineEvents = $timelineModel->getCoupleEvents($coupleId);

        $stats = [
            'total_messages' => $messageModel->count('couple_id = :cid', ['cid' => $coupleId]),
            'total_photos'   => $photoModel->count('couple_id = :cid', ['cid' => $coupleId]),
            'total_memories' => $memoryModel->count('couple_id = :cid', ['cid' => $coupleId]),
            'duration'       => format_relationship_duration($couple['relationship_start_date'])
        ];

        view('dashboard/index', [
            'couple'         => $couple,
            'partner'        => $partner,
            'me'             => $me,
            'recentMessages' => $recentMessages,
            'recentPhotos'   => $recentPhotos,
            'memories'       => $memories,
            'timelineEvents' => $timelineEvents,
            'stats'          => $stats,
            'pageTitle'      => 'Dashboard — Our Private Corner'
        ]);

        unset($_SESSION['flash_error'], $_SESSION['flash_success']);
    }
}
