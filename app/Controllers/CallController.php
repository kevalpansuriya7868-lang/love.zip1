<?php

require_once __DIR__ . '/../Middleware/AuthMiddleware.php';
require_once __DIR__ . '/../Models/Call.php';
require_once __DIR__ . '/../Models/Couple.php';

class CallController {
    
    private function jsonResponse($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    private function getJsonPayload() {
        return json_decode(file_get_contents('php://input'), true) ?? [];
    }

    public function initiate() {
        AuthMiddleware::handleAjax();
        $payload = $this->getJsonPayload();
        
        $coupleId = $_SESSION['couple']['id'];
        $callerId = $_SESSION['user']['id'];
        
        $coupleModel = new Couple();
        $partner = $coupleModel->getPartnerOf($coupleId, $callerId);
        if (!$partner) return $this->jsonResponse(['success' => false, 'error' => 'Partner not found']);
        
        $type = $payload['type'] ?? 'video';
        $offer = $payload['offer'] ?? '';
        
        $callModel = new Call();
        $callModel->endActiveCalls($coupleId); // end previous
        
        $callId = $callModel->createCall($coupleId, $callerId, $partner['id'], $type, $offer);
        
        return $this->jsonResponse(['success' => true, 'call_id' => $callId]);
    }

    public function poll() {
        AuthMiddleware::handleAjax();
        $coupleId = $_SESSION['couple']['id'];
        $userId = $_SESSION['user']['id'];
        
        $callModel = new Call();
        $call = $callModel->getActiveCall($coupleId);
        
        if (!$call) {
            return $this->jsonResponse(['call' => null]);
        }
        
        return $this->jsonResponse(['call' => $call, 'user_id' => $userId]);
    }

    public function answer() {
        AuthMiddleware::handleAjax();
        $payload = $this->getJsonPayload();
        $callId = $payload['call_id'] ?? 0;
        $answer = $payload['answer'] ?? '';
        
        $callModel = new Call();
        $callModel->answerCall($callId, $answer);
        
        return $this->jsonResponse(['success' => true]);
    }

    public function reject() {
        AuthMiddleware::handleAjax();
        $payload = $this->getJsonPayload();
        $callId = $payload['call_id'] ?? 0;
        
        $callModel = new Call();
        $callModel->rejectCall($callId);
        
        return $this->jsonResponse(['success' => true]);
    }

    public function end() {
        AuthMiddleware::handleAjax();
        $payload = $this->getJsonPayload();
        $callId = $payload['call_id'] ?? 0;
        
        $callModel = new Call();
        $callModel->endCall($callId);
        
        return $this->jsonResponse(['success' => true]);
    }

    public function candidate() {
        AuthMiddleware::handleAjax();
        $payload = $this->getJsonPayload();
        
        $callId = $payload['call_id'] ?? 0;
        $candidate = $payload['candidate'] ?? null;
        $type = $payload['type'] ?? 'caller'; // caller or receiver
        
        if ($callId && $candidate) {
            $callModel = new Call();
            $callModel->addCandidate($callId, $type, $candidate);
        }
        
        return $this->jsonResponse(['success' => true]);
    }
}
