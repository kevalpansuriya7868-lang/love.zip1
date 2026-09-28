<?php

require_once __DIR__ . '/../../Middleware/AdminMiddleware.php';
require_once __DIR__ . '/../../Models/Photo.php';
require_once __DIR__ . '/../../Models/Couple.php';

class AdminPhotoController {

    public function index() {
        AdminMiddleware::handle();

        $photoModel  = new Photo();
        $coupleModel = new Couple();

        $coupleId = !empty($_GET['couple_id']) ? (int)$_GET['couple_id'] : null;
        $search   = trim($_GET['search'] ?? '');

        $photos  = $photoModel->getAllPhotosAdmin($coupleId, $search, 100);
        $couples = $coupleModel->all('name ASC');

        view('admin/photos/index', [
            'photos'    => $photos,
            'couples'   => $couples,
            'coupleId'  => $coupleId,
            'search'    => $search,
            'pageTitle' => 'Admin Photo Moderation'
        ], 'layouts/admin_header');

        unset($_SESSION['flash_error'], $_SESSION['flash_success']);
    }

    public function delete() {
        AdminMiddleware::handle();

        $photoId = (int)($_POST['id'] ?? 0);
        $photoModel = new Photo();
        $photo = $photoModel->find($photoId);

        if ($photo) {
            $filePath = config('app.upload_dir') . '/' . $photo['file_path'];
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
            $photoModel->delete($photoId);
            log_activity('admin_delete_photo', "Admin deleted photo ID {$photoId}", $_SESSION['admin']['id'], null, $photo['couple_id']);
            $_SESSION['flash_success'] = 'Photo deleted successfully.';
        }

        redirect('admin/photos');
    }
}
