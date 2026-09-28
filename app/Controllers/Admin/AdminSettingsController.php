<?php

require_once __DIR__ . '/../../Middleware/AdminMiddleware.php';
require_once __DIR__ . '/../../Models/Setting.php';

class AdminSettingsController {

    public function index() {
        AdminMiddleware::handle();

        $settingModel = new Setting();
        $settings = $settingModel->getAllAsAssoc();

        view('admin/settings/index', [
            'settings'  => $settings,
            'pageTitle' => 'System Settings'
        ], 'layouts/admin_header');

        unset($_SESSION['flash_error'], $_SESSION['flash_success']);
    }

    public function update() {
        AdminMiddleware::handle();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $settingModel = new Setting();

            $updatableKeys = [
                'site_name', 'timezone', 'maintenance_mode',
                'message_retention', 'message_retention_days', 'allow_user_message_delete', 'allow_image_messages', 'typing_indicator',
                'max_file_size_mb', 'allowed_file_types', 'enable_photo_downloads', 'enable_photo_deletion',
                'login_max_attempts', 'session_timeout_minutes'
            ];

            foreach ($updatableKeys as $key) {
                if (isset($_POST[$key])) {
                    $settingModel->setValue($key, trim($_POST[$key]));
                }
            }

            log_activity('admin_update_settings', "Updated system configuration settings", $_SESSION['admin']['id']);
            $_SESSION['flash_success'] = 'System settings updated successfully.';
            redirect('admin/settings');
        }
    }
}
