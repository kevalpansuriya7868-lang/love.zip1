<?php

require_once __DIR__ . '/../../Middleware/AdminMiddleware.php';
require_once __DIR__ . '/../../Models/Couple.php';
require_once __DIR__ . '/../../Models/User.php';
require_once __DIR__ . '/../../Models/Message.php';

class AdminChatController {

    public function index() {
        AdminMiddleware::handle();

        $coupleModel  = new Couple();
        $messageModel = new Message();

        $couples = $coupleModel->all('name ASC');

        $selectedCoupleId = !empty($_GET['couple_id']) ? (int)$_GET['couple_id'] : ($couples[0]['id'] ?? 0);
        $search   = trim($_GET['search'] ?? '');
        $senderId = !empty($_GET['sender_id']) ? (int)$_GET['sender_id'] : null;
        $dateFrom = $_GET['date_from'] ?? null;
        $dateTo   = $_GET['date_to'] ?? null;

        $messages = [];
        $selectedCouple = null;
        $partners = [];

        if ($selectedCoupleId) {
            $selectedCouple = $coupleModel->find($selectedCoupleId);
            $partners = $coupleModel->getPartners($selectedCoupleId);
            $messages = $messageModel->getAdminMessages($selectedCoupleId, $search, $senderId, $dateFrom, $dateTo, 100);
        }

        view('admin/messages/index', [
            'couples'        => $couples,
            'selectedCouple' => $selectedCouple,
            'partners'       => $partners,
            'messages'       => $messages,
            'search'         => $search,
            'senderId'       => $senderId,
            'dateFrom'       => $dateFrom,
            'dateTo'         => $dateTo,
            'pageTitle'      => 'Admin Chat History Monitor'
        ], 'layouts/admin_header');

        unset($_SESSION['flash_error'], $_SESSION['flash_success']);
    }

    public function deleteMessage() {
        AdminMiddleware::handle();

        $msgId = (int)($_POST['id'] ?? 0);
        $messageModel = new Message();
        $msg = $messageModel->find($msgId);

        if ($msg) {
            $messageModel->softDelete($msgId);
            log_activity('admin_delete_message', "Admin deleted message ID {$msgId}", $_SESSION['admin']['id'], null, $msg['couple_id']);
            $_SESSION['flash_success'] = 'Message content removed by admin.';
        }

        $coupleId = $msg ? $msg['couple_id'] : '';
        redirect("admin/messages?couple_id={$coupleId}");
    }
}
