<?php

require_once __DIR__ . '/../Middleware/AuthMiddleware.php';
require_once __DIR__ . '/../Models/Photo.php';
require_once __DIR__ . '/../Models/Couple.php';

class PhotoController {

    public function index() {
        AuthMiddleware::handle();

        $coupleId = $_SESSION['couple']['id'];
        $photoModel = new Photo();
        require_once __DIR__ . '/../Models/PhotoFolder.php';
        $folderModel = new PhotoFolder();

        if (isset($_GET['folder_id'])) {
            $folderId = (int)$_GET['folder_id'];
            $folder = $folderModel->getFolder($folderId, $coupleId);
            
            if (!$folder) {
                $_SESSION['flash_error'] = "Folder not found.";
                header("Location: " . url('photos'));
                exit;
            }

            $photos = $photoModel->getCouplePhotos($coupleId, 100, 0, $folderId);

            view('photos/index', [
                'photos'    => $photos,
                'folder'    => $folder,
                'folders'   => [],
                'pageTitle' => e($folder['name']) . ' — Shared Moments'
            ]);
        } else {
            $folders = $folderModel->getCoupleFolders($coupleId);
            $photos = $photoModel->getCouplePhotos($coupleId, 100, 0, null); // Unfiled photos

            view('photos/index', [
                'photos'    => $photos,
                'folders'   => $folders,
                'folder'    => null,
                'pageTitle' => 'Photo Gallery — Shared Moments'
            ]);
        }

        unset($_SESSION['flash_error'], $_SESSION['flash_success']);
    }

    public function downloadAll() {
        AuthMiddleware::handle();
        $coupleId = $_SESSION['couple']['id'];
        $photoModel = new Photo();
        
        $photos = $photoModel->getCouplePhotos($coupleId, 1000); 
        
        if (empty($photos)) {
            $_SESSION['flash_error'] = "No photos available to download.";
            header("Location: " . url('photos'));
            exit;
        }

        $zip = new ZipArchive();
        $zipName = 'Our_Shared_Moments_' . date('Ymd_His') . '.zip';
        $zipPath = sys_get_temp_dir() . '/' . $zipName;

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            $added = 0;
            foreach ($photos as $photo) {
                $filePath = __DIR__ . '/../../public/uploads/' . ltrim($photo['file_path'], '/');
                if (file_exists($filePath) && is_file($filePath)) {
                    $fileName = basename($filePath);
                    $zip->addFile($filePath, $photo['id'] . '_' . $fileName);
                    $added++;
                }
            }
            $zip->close();
            
            if ($added > 0) {
                header('Content-Type: application/zip');
                header('Content-Disposition: attachment; filename="' . $zipName . '"');
                header('Content-Length: ' . filesize($zipPath));
                readfile($zipPath);
                unlink($zipPath);
                exit;
            } else {
                $_SESSION['flash_error'] = "Files could not be found on the server.";
            }
        } else {
            $_SESSION['flash_error'] = "Failed to create zip archive.";
        }
        
        header("Location: " . url('photos'));
        exit;
    }
}
