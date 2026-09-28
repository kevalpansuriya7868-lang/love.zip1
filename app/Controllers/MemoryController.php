<?php

require_once __DIR__ . '/../Middleware/AuthMiddleware.php';
require_once __DIR__ . '/../Models/Memory.php';
require_once __DIR__ . '/../Services/UploadService.php';

class MemoryController {

    public function index() {
        AuthMiddleware::handle();

        $coupleId = $_SESSION['couple']['id'];
        $memoryModel = new Memory();

        $memories = $memoryModel->getCoupleMemories($coupleId, 50);

        // Attach additional photos
        foreach ($memories as &$m) {
            $m['photos'] = $memoryModel->getMemoryPhotos($m['id']);
        }

        view('memories/index', [
            'memories'  => $memories,
            'pageTitle' => 'Our Scrapbook Memories'
        ]);

        unset($_SESSION['flash_error'], $_SESSION['flash_success']);
    }

    public function create() {
        AuthMiddleware::handle();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title       = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $memoryDate  = $_POST['memory_date'] ?? date('Y-m-d');
            $location    = trim($_POST['location'] ?? '');
            $coupleId    = $_SESSION['couple']['id'];
            $userId      = $_SESSION['user']['id'];

            if (empty($title)) {
                $_SESSION['flash_error'] = 'Memory title is required.';
                redirect('memories');
            }

            $coverPath = null;
            if (!empty($_FILES['cover_photo']['name'])) {
                $upload = UploadService::uploadImage($_FILES['cover_photo'], 'memories');
                if ($upload['success']) {
                    $coverPath = $upload['file_path'];
                } else {
                    $_SESSION['flash_error'] = $upload['error'];
                    redirect('memories');
                }
            }

            $memoryModel = new Memory();
            $memoryId = $memoryModel->create([
                'couple_id'   => $coupleId,
                'title'       => $title,
                'description' => $description,
                'memory_date' => $memoryDate,
                'location'    => $location,
                'cover_photo' => $coverPath,
                'created_by'  => $userId
            ]);

            // Handle multiple extra photos if uploaded
            if (!empty($_FILES['additional_photos']['name'][0])) {
                $files = $_FILES['additional_photos'];
                for ($i = 0; $i < count($files['name']); $i++) {
                    $single = [
                        'name'     => $files['name'][$i],
                        'type'     => $files['type'][$i],
                        'tmp_name' => $files['tmp_name'][$i],
                        'error'    => $files['error'][$i],
                        'size'     => $files['size'][$i]
                    ];
                    $upload = UploadService::uploadImage($single, 'memories');
                    if ($upload['success']) {
                        $memoryModel->addMemoryPhoto($memoryId, $upload['file_path']);
                    }
                }
            }

            log_activity('create_memory', "Created memory: {$title}", null, $userId, $coupleId);
            $_SESSION['flash_success'] = 'Memory saved successfully!';
            redirect('memories');
        }

        view('memories/create', [
            'pageTitle' => 'Create New Memory'
        ]);
    }
}
