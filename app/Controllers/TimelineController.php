<?php

require_once __DIR__ . '/../Middleware/AuthMiddleware.php';
require_once __DIR__ . '/../Models/TimelineEvent.php';
require_once __DIR__ . '/../Services/UploadService.php';

class TimelineController {

    public function index() {
        AuthMiddleware::handle();

        $coupleId = $_SESSION['couple']['id'];
        $timelineModel = new TimelineEvent();

        $events = $timelineModel->getCoupleEvents($coupleId);

        view('timeline/index', [
            'events'    => $events,
            'pageTitle' => 'Relationship Timeline — Our Journey'
        ]);

        unset($_SESSION['flash_error'], $_SESSION['flash_success']);
    }

    public function store() {
        AuthMiddleware::handle();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title       = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $eventDate   = $_POST['event_date'] ?? date('Y-m-d');
            $icon        = trim($_POST['icon'] ?? 'fa-heart');
            $coupleId    = $_SESSION['couple']['id'];
            $userId      = $_SESSION['user']['id'];

            if (empty($title)) {
                $_SESSION['flash_error'] = 'Timeline event title is required.';
                redirect('timeline');
            }

            $imagePath = null;
            if (!empty($_FILES['image']['name'])) {
                $upload = UploadService::uploadImage($_FILES['image'], 'timeline');
                if ($upload['success']) {
                    $imagePath = $upload['file_path'];
                }
            }

            $timelineModel = new TimelineEvent();
            $timelineModel->create([
                'couple_id'   => $coupleId,
                'title'       => $title,
                'description' => $description,
                'event_date' => $eventDate,
                'icon'        => $icon,
                'image'       => $imagePath,
                'created_by'  => $userId
            ]);

            log_activity('add_timeline_event', "Added milestone: {$title}", null, $userId, $coupleId);
            $_SESSION['flash_success'] = 'New milestone added to your timeline!';
            redirect('timeline');
        }
    }
}
