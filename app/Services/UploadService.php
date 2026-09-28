<?php

class UploadService {
    public static function uploadImage($file, $subFolder = 'photos') {
        $validation = Security::validateImageUpload($file, config('app.max_file_size', 10485760));
        if (!$validation['valid']) {
            return ['success' => false, 'error' => $validation['error']];
        }

        $filename = Security::generateSafeFilename($validation['ext']);
        $uploadDir = config('app.upload_dir') . '/' . trim($subFolder, '/');

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $targetPath = $uploadDir . '/' . $filename;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            $relativePath = $subFolder . '/' . $filename;
            return [
                'success'       => true,
                'filename'      => $file['name'],
                'file_path'     => $relativePath,
                'absolute_path' => $targetPath
            ];
        }

        return ['success' => false, 'error' => 'Failed to move uploaded file.'];
    }
}
