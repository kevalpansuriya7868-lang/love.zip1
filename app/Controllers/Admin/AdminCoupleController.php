<?php

require_once __DIR__ . '/../../Middleware/AdminMiddleware.php';
require_once __DIR__ . '/../../Models/Couple.php';
require_once __DIR__ . '/../../Models/User.php';
require_once __DIR__ . '/../../Services/UploadService.php';

class AdminCoupleController {

    public function index() {
        AdminMiddleware::handle();

        $coupleModel = new Couple();
        $status      = $_GET['status'] ?? null;
        $search      = trim($_GET['search'] ?? '');

        $sql = "WHERE 1=1";
        $params = [];

        if ($status) {
            $sql .= " AND status = :status";
            $params['status'] = $status;
        }

        if ($search) {
            $sql .= " AND (name LIKE :search OR slug LIKE :search)";
            $params['search'] = "%{$search}%";
        }

        $couples = $coupleModel->where(ltrim($sql, 'WHERE '), $params, 'id DESC');

        foreach ($couples as &$c) {
            $c['partner_1'] = $coupleModel->getPartnerOne($c['id']);
            $c['partner_2'] = $coupleModel->getPartnerTwo($c['id']);
        }

        view('admin/couples/index', [
            'couples'   => $couples,
            'search'    => $search,
            'status'    => $status,
            'pageTitle' => 'Manage Couples'
        ], 'layouts/admin_header');

        unset($_SESSION['flash_error'], $_SESSION['flash_success']);
    }

    public function create() {
        AdminMiddleware::handle();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $coupleName      = trim($_POST['couple_name'] ?? '');
            $startDate       = $_POST['relationship_start_date'] ?? date('Y-m-d');
            $bio             = trim($_POST['bio'] ?? '');
            $status          = $_POST['status'] ?? 'active';

            // Partner 1 fields
            $p1FirstName     = trim($_POST['p1_first_name'] ?? '');
            $p1LastName      = trim($_POST['p1_last_name'] ?? '');
            $p1Email         = trim($_POST['p1_email'] ?? '');
            $p1Password      = $_POST['p1_password'] ?? '';
            $p1Birthday      = $_POST['p1_birthday'] ?? null;

            // Partner 2 fields
            $p2FirstName     = trim($_POST['p2_first_name'] ?? '');
            $p2LastName      = trim($_POST['p2_last_name'] ?? '');
            $p2Email         = trim($_POST['p2_email'] ?? '');
            $p2Password      = $_POST['p2_password'] ?? '';
            $p2Birthday      = $_POST['p2_birthday'] ?? null;

            if (empty($coupleName) || empty($p1Email) || empty($p1Password) || empty($p2Email) || empty($p2Password)) {
                $_SESSION['flash_error'] = 'Please fill in all required fields.';
                redirect('admin/couples/create');
            }

            $userModel = new User();
            if ($userModel->findByEmail($p1Email)) {
                $_SESSION['flash_error'] = "Email/Username {$p1Email} is already in use.";
                redirect('admin/couples/create');
            }
            if ($userModel->findByEmail($p2Email)) {
                $_SESSION['flash_error'] = "Email/Username {$p2Email} is already in use.";
                redirect('admin/couples/create');
            }

            $slug = strtolower(preg_replace('/[^A-Za-z0-9-]+/', '-', $coupleName)) . '-' . rand(1000, 9999);

            $couplePhoto = null;
            if (!empty($_FILES['profile_photo']['name'])) {
                $upload = UploadService::uploadImage($_FILES['profile_photo'], 'profiles');
                if ($upload['success']) {
                    $couplePhoto = $upload['file_path'];
                }
            }

            $coupleModel = new Couple();
            $coupleId = $coupleModel->create([
                'name'                    => $coupleName,
                'slug'                    => $slug,
                'relationship_start_date' => $startDate,
                'profile_photo'           => $couplePhoto,
                'bio'                     => $bio,
                'status'                  => $status
            ]);

            // Create Partner 1
            $p1Photo = null;
            if (!empty($_FILES['p1_photo']['name'])) {
                $up = UploadService::uploadImage($_FILES['p1_photo'], 'profiles');
                if ($up['success']) $p1Photo = $up['file_path'];
            }
            $userModel->create([
                'couple_id'     => $coupleId,
                'name'          => trim("{$p1FirstName} {$p1LastName}"),
                'email'         => $p1Email,
                'password'      => password_hash($p1Password, PASSWORD_DEFAULT),
                'profile_photo' => $p1Photo,
                'role'          => 'partner_1',
                'birthday'      => $p1Birthday,
                'status'        => 'active'
            ]);

            // Create Partner 2
            $p2Photo = null;
            if (!empty($_FILES['p2_photo']['name'])) {
                $up = UploadService::uploadImage($_FILES['p2_photo'], 'profiles');
                if ($up['success']) $p2Photo = $up['file_path'];
            }
            $userModel->create([
                'couple_id'     => $coupleId,
                'name'          => trim("{$p2FirstName} {$p2LastName}"),
                'email'         => $p2Email,
                'password'      => password_hash($p2Password, PASSWORD_DEFAULT),
                'profile_photo' => $p2Photo,
                'role'          => 'partner_2',
                'birthday'      => $p2Birthday,
                'status'        => 'active'
            ]);

            log_activity('admin_create_couple', "Created couple: {$coupleName}", $_SESSION['admin']['id'], null, $coupleId);

            $_SESSION['created_couple_credentials'] = [
                'couple_name' => $coupleName,
                'p1_email'    => $p1Email,
                'p1_password' => $p1Password,
                'p2_email'    => $p2Email,
                'p2_password' => $p2Password,
                'login_url'   => url('login')
            ];

            redirect('admin/couples/created-success');
        }

        view('admin/couples/create', [
            'pageTitle' => 'Create New Couple'
        ], 'layouts/admin_header');

        unset($_SESSION['flash_error'], $_SESSION['flash_success']);
    }

    public function successScreen() {
        AdminMiddleware::handle();

        $creds = $_SESSION['created_couple_credentials'] ?? null;
        if (!$creds) {
            redirect('admin/couples');
        }

        view('admin/couples/success', [
            'creds'     => $creds,
            'pageTitle' => 'Couple Created Successfully'
        ], 'layouts/admin_header');
    }

    public function edit() {
        AdminMiddleware::handle();

        $coupleId = (int)($_GET['id'] ?? 0);
        $coupleModel = new Couple();
        $userModel   = new User();

        $couple = $coupleModel->find($coupleId);
        if (!$couple) {
            $_SESSION['flash_error'] = 'Couple not found.';
            redirect('admin/couples');
        }

        $p1 = $coupleModel->getPartnerOne($coupleId);
        $p2 = $coupleModel->getPartnerTwo($coupleId);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name      = trim($_POST['couple_name'] ?? '');
            $startDate = $_POST['relationship_start_date'] ?? date('Y-m-d');
            $bio       = trim($_POST['bio'] ?? '');
            $status    = $_POST['status'] ?? 'active';

            $coupleData = [
                'name'                    => $name,
                'relationship_start_date' => $startDate,
                'bio'                     => $bio,
                'status'                  => $status
            ];

            if (!empty($_FILES['profile_photo']['name'])) {
                $upload = UploadService::uploadImage($_FILES['profile_photo'], 'profiles');
                if ($upload['success']) {
                    $coupleData['profile_photo'] = $upload['file_path'];
                }
            }

            $coupleModel->update($coupleId, $coupleData);

            // Update or Create Partner 1
            $p1Name  = trim($_POST['p1_name'] ?? '');
            $p1Email = trim($_POST['p1_email'] ?? '');
            if (!empty($p1Name)) {
                $p1Data = ['name' => $p1Name];
                if (!empty($p1Email)) $p1Data['email'] = $p1Email;
                if (!empty($_POST['p1_password'])) {
                    $p1Data['password'] = password_hash($_POST['p1_password'], PASSWORD_DEFAULT);
                }

                if ($p1) {
                    $userModel->update($p1['id'], $p1Data);
                } else {
                    $p1Data['couple_id'] = $coupleId;
                    $p1Data['role'] = 'partner_1';
                    $p1Data['password'] = password_hash($_POST['p1_password'] ?: 'puser1', PASSWORD_DEFAULT);
                    $userModel->create($p1Data);
                }
            }

            // Update or Create Partner 2
            $p2Name  = trim($_POST['p2_name'] ?? '');
            $p2Email = trim($_POST['p2_email'] ?? '');
            if (!empty($p2Name)) {
                $p2Data = ['name' => $p2Name];
                if (!empty($p2Email)) $p2Data['email'] = $p2Email;
                if (!empty($_POST['p2_password'])) {
                    $p2Data['password'] = password_hash($_POST['p2_password'], PASSWORD_DEFAULT);
                }

                if ($p2) {
                    $userModel->update($p2['id'], $p2Data);
                } else {
                    $p2Data['couple_id'] = $coupleId;
                    $p2Data['role'] = 'partner_2';
                    $p2Data['password'] = password_hash($_POST['p2_password'] ?: 'puser1', PASSWORD_DEFAULT);
                    $userModel->create($p2Data);
                }
            }

            log_activity('admin_edit_couple', "Updated couple: {$name}", $_SESSION['admin']['id'], null, $coupleId);
            $_SESSION['flash_success'] = 'Couple and partners updated successfully.';
            redirect("admin/couples/edit?id={$coupleId}");
        }

        view('admin/couples/edit', [
            'couple'    => $couple,
            'p1'        => $p1,
            'p2'        => $p2,
            'pageTitle' => 'Edit Couple'
        ], 'layouts/admin_header');

        unset($_SESSION['flash_error'], $_SESSION['flash_success']);
    }

    public function delete() {
        AdminMiddleware::handle();

        $coupleId = (int)($_POST['id'] ?? 0);
        $coupleModel = new Couple();
        $couple = $coupleModel->find($coupleId);

        if ($couple) {
            $coupleModel->delete($coupleId);
            log_activity('admin_delete_couple', "Deleted couple: {$couple['name']}", $_SESSION['admin']['id']);
            $_SESSION['flash_success'] = "Couple {$couple['name']} deleted successfully.";
        }

        redirect('admin/couples');
    }
}
