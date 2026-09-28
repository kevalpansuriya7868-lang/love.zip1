<?php

require_once __DIR__ . '/../../Models/Message.php';
require_once __DIR__ . '/../../Models/Notification.php';
require_once __DIR__ . '/../../Models/Couple.php';
require_once __DIR__ . '/../../Services/UploadService.php';

class ChatApiController {

    private function checkAuth() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!is_logged_in()) {
            json_response(['success' => false, 'message' => 'Unauthorized access'], 401);
        }
    }

    public function send() {
        $this->checkAuth();

        $coupleId = $_SESSION['couple']['id'];
        $senderId = $_SESSION['user']['id'];
        $text     = trim($_POST['message'] ?? '');
        $replyTo  = !empty($_POST['reply_to_id']) ? (int)$_POST['reply_to_id'] : null;

        $imagePath = null;
        $messageType = 'text';

        if (!empty($_FILES['image']['name'])) {
            $upload = UploadService::uploadImage($_FILES['image'], 'photos');
            if ($upload['success']) {
                $imagePath = $upload['file_path'];
                $messageType = 'image';
            } else {
                json_response(['success' => false, 'message' => $upload['error']], 400);
            }
        }

        if (empty($text) && empty($imagePath)) {
            json_response(['success' => false, 'message' => 'Cannot send an empty message.'], 400);
        }

        $messageContent = $imagePath ? $imagePath : $text;

        $messageModel = new Message();
        $msgId = $messageModel->create([
            'couple_id'    => $coupleId,
            'sender_id'    => $senderId,
            'message'      => $messageContent,
            'message_type' => $messageType,
            'reply_to_id'  => $replyTo,
            'is_read'      => 0
        ]);

        // Send notification to partner
        $coupleModel = new Couple();
        $partner = $coupleModel->getPartnerOf($coupleId, $senderId);
        if ($partner) {
            $notifModel = new Notification();
            $notifModel->create([
                'user_id' => $partner['id'],
                'type'    => 'new_message',
                'title'   => 'New Message',
                'message' => $_SESSION['user']['name'] . ': ' . ($messageType === 'image' ? '📷 Sent a photo' : mb_substr($text, 0, 50))
            ]);
        }

        $msg = $messageModel->find($msgId);
        $msg['sender_name'] = $_SESSION['user']['name'];
        $msg['sender_photo'] = $_SESSION['user']['profile_photo'];

        json_response([
            'success' => true,
            'message' => 'Message sent successfully',
            'data'    => $msg
        ]);
    }

    public function messages() {
        $this->checkAuth();

        $coupleId = $_SESSION['couple']['id'];
        $userId   = $_SESSION['user']['id'];
        $afterId  = isset($_GET['after_id']) ? (int)$_GET['after_id'] : 0;

        $messageModel = new Message();

        if ($afterId > 0) {
            $messages = $messageModel->getNewMessages($coupleId, $afterId);
        } else {
            $messages = $messageModel->getRecentMessages($coupleId, 50);
        }

        $messageModel->markAsRead($coupleId, $userId);

        json_response([
            'success' => true,
            'data'    => $messages
        ]);
    }

    public function delete() {
        $this->checkAuth();

        $messageId = (int)($_POST['id'] ?? 0);
        if (!$messageId) {
            json_response(['success' => false, 'message' => 'Invalid message ID'], 400);
        }

        $messageModel = new Message();
        $msg = $messageModel->find($messageId);

        if (!$msg || $msg['couple_id'] != $_SESSION['couple']['id']) {
            json_response(['success' => false, 'message' => 'Message not found or unauthorized'], 403);
        }

        // Only sender or admin can delete
        if ($msg['sender_id'] != $_SESSION['user']['id']) {
            json_response(['success' => false, 'message' => 'You can only delete your own messages'], 403);
        }

        $messageModel->softDelete($messageId);

        json_response([
            'success' => true,
            'message' => 'Message deleted'
        ]);
    }

    public function read() {
        $this->checkAuth();

        $coupleId = $_SESSION['couple']['id'];
        $userId   = $_SESSION['user']['id'];

        $messageModel = new Message();
        $messageModel->markAsRead($coupleId, $userId);

        json_response(['success' => true]);
    }
}
