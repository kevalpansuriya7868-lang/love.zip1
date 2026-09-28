<?php

require_once __DIR__ . '/../../Models/Photo.php';
require_once __DIR__ . '/../../Models/Notification.php';
require_once __DIR__ . '/../../Models/Couple.php';
require_once __DIR__ . '/../../Services/UploadService.php';
require_once __DIR__ . '/../../Models/PhotoFolder.php';

class PhotoApiController {

    private function checkAuth() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!is_logged_in()) {
            json_response(['success' => false, 'message' => 'Unauthorized access'], 401);
        }
    }

    public function upload() {
        $this->checkAuth();

        if (empty($_FILES['photo']['name'])) {
            json_response(['success' => false, 'message' => 'No photo file provided.'], 400);
        }

        $caption  = trim($_POST['caption'] ?? '');
        $coupleId = $_SESSION['couple']['id'];
        $userId   = $_SESSION['user']['id'];

        $upload = UploadService::uploadImage($_FILES['photo'], 'photos');
        if (!$upload['success']) {
            json_response(['success' => false, 'message' => $upload['error']], 400);
        }

        $folderId = !empty($_POST['folder_id']) ? (int)$_POST['folder_id'] : null;

        $photoModel = new Photo();
        $photoId = $photoModel->create([
            'couple_id'   => $coupleId,
            'uploaded_by' => $userId,
            'folder_id'   => $folderId,
            'filename'    => $upload['filename'],
            'file_path'   => $upload['file_path'],
            'caption'     => $caption
        ]);

        // Notify partner
        $coupleModel = new Couple();
        $partner = $coupleModel->getPartnerOf($coupleId, $userId);
        if ($partner) {
            $notifModel = new Notification();
            $notifModel->create([
                'user_id' => $partner['id'],
                'type'    => 'new_photo',
                'title'   => 'New Photo Uploaded',
                'message' => $_SESSION['user']['name'] . ' added a new photo to your album.'
            ]);
        }

        log_activity('upload_photo', "Uploaded photo: {$upload['filename']}", null, $userId, $coupleId);

        $photo = $photoModel->find($photoId);
        $photo['uploader_name'] = $_SESSION['user']['name'];

        json_response([
            'success' => true,
            'message' => 'Photo uploaded successfully',
            'data'    => $photo
        ]);
    }

    public function delete() {
        $this->checkAuth();

        $photoId = (int)($_POST['id'] ?? 0);
        if (!$photoId) {
            json_response(['success' => false, 'message' => 'Invalid photo ID.'], 400);
        }

        $photoModel = new Photo();
        $photo = $photoModel->find($photoId);

        if (!$photo || $photo['couple_id'] != $_SESSION['couple']['id']) {
            json_response(['success' => false, 'message' => 'Photo not found or unauthorized.'], 403);
        }

        // Only uploader or admin can delete
        if ($photo['uploaded_by'] != $_SESSION['user']['id']) {
            json_response(['success' => false, 'message' => 'You can only delete photos uploaded by you.'], 403);
        }

        // Unlink file if exists
        $filePath = config('app.upload_dir') . '/' . $photo['file_path'];
        if (file_exists($filePath)) {
            @unlink($filePath);
        }

        $photoModel->delete($photoId);

        log_activity('delete_photo', "Deleted photo ID {$photoId}", null, $_SESSION['user']['id'], $_SESSION['couple']['id']);

        json_response([
            'success' => true,
            'message' => 'Photo deleted successfully'
        ]);
    }

    public function createFolder() {
        $this->checkAuth();

        $name = trim($_POST['name'] ?? '');
        if (empty($name)) {
            json_response(['success' => false, 'message' => 'Folder name is required.'], 400);
        }

        $folderModel = new PhotoFolder();
        $folderId = $folderModel->create([
            'couple_id'  => $_SESSION['couple']['id'],
            'name'       => $name,
            'created_by' => $_SESSION['user']['id']
        ]);

        log_activity('create_folder', "Created folder: {$name}", null, $_SESSION['user']['id'], $_SESSION['couple']['id']);

        json_response([
            'success' => true,
            'message' => 'Folder created successfully'
        ]);
    }

    public function deleteFolder() {
        $this->checkAuth();

        $folderId = (int)($_POST['id'] ?? 0);
        if (!$folderId) {
            json_response(['success' => false, 'message' => 'Invalid folder ID.'], 400);
        }

        $folderModel = new PhotoFolder();
        $folder = $folderModel->find($folderId);

        if (!$folder || $folder['couple_id'] != $_SESSION['couple']['id']) {
            json_response(['success' => false, 'message' => 'Folder not found or unauthorized.'], 403);
        }

        // Only creator can delete
        if ($folder['created_by'] != $_SESSION['user']['id']) {
            json_response(['success' => false, 'message' => 'You can only delete folders created by you.'], 403);
        }

        // Delete folder
        $folderModel->delete($folderId);

        log_activity('delete_folder', "Deleted folder ID {$folderId}", null, $_SESSION['user']['id'], $_SESSION['couple']['id']);

        json_response([
            'success' => true,
            'message' => 'Folder deleted successfully'
        ]);
    }
}
