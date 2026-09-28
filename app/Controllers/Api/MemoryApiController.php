<?php

require_once __DIR__ . '/../../Models/Memory.php';

class MemoryApiController {

    private function checkAuth() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!is_logged_in()) {
            json_response(['success' => false, 'message' => 'Unauthorized access'], 401);
        }
    }

    public function delete() {
        $this->checkAuth();

        $memoryId = (int)($_POST['id'] ?? 0);
        if (!$memoryId) {
            json_response(['success' => false, 'message' => 'Invalid memory ID.'], 400);
        }

        $memoryModel = new Memory();
        $memory = $memoryModel->find($memoryId);

        if (!$memory || $memory['couple_id'] != $_SESSION['couple']['id']) {
            json_response(['success' => false, 'message' => 'Memory not found or unauthorized.'], 403);
        }

        // Delete associated files
        if ($memory['cover_photo']) {
            $coverFile = config('app.upload_dir') . '/' . $memory['cover_photo'];
            if (file_exists($coverFile)) @unlink($coverFile);
        }

        $extraPhotos = $memoryModel->getMemoryPhotos($memoryId);
        foreach ($extraPhotos as $p) {
            $f = config('app.upload_dir') . '/' . $p['file_path'];
            if (file_exists($f)) @unlink($f);
        }

        $memoryModel->deleteMemoryPhotos($memoryId);
        $memoryModel->delete($memoryId);

        log_activity('delete_memory', "Deleted memory: {$memory['title']}", null, $_SESSION['user']['id'], $_SESSION['couple']['id']);

        json_response([
            'success' => true,
            'message' => 'Memory deleted successfully.'
        ]);
    }
}
