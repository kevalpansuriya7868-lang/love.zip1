<?php

require_once __DIR__ . '/Model.php';

class Call extends Model {
    protected $table = 'calls';

    public function endActiveCalls($coupleId) {
        $stmt = $this->db()->prepare("UPDATE calls SET status = 'ended' WHERE couple_id = :couple_id AND status IN ('initiating', 'ringing', 'answered')");
        return $stmt->execute(['couple_id' => $coupleId]);
    }

    public function createCall($coupleId, $callerId, $receiverId, $type, $offer) {
        $stmt = $this->db()->prepare("INSERT INTO calls (couple_id, caller_id, receiver_id, type, status, offer) VALUES (:cid, :caller, :receiver, :type, 'ringing', :offer)");
        $stmt->execute([
            'cid' => $coupleId,
            'caller' => $callerId,
            'receiver' => $receiverId,
            'type' => $type,
            'offer' => $offer
        ]);
        return $this->db()->lastInsertId();
    }

    public function getActiveCall($coupleId) {
        $stmt = $this->db()->prepare("SELECT * FROM calls WHERE couple_id = :cid AND status IN ('initiating', 'ringing', 'answered') ORDER BY id DESC LIMIT 1");
        $stmt->execute(['cid' => $coupleId]);
        return $stmt->fetch();
    }

    public function answerCall($callId, $answer) {
        $stmt = $this->db()->prepare("UPDATE calls SET status = 'answered', answer = :answer WHERE id = :id");
        return $stmt->execute(['answer' => $answer, 'id' => $callId]);
    }

    public function endCall($callId) {
        $stmt = $this->db()->prepare("UPDATE calls SET status = 'ended' WHERE id = :id");
        return $stmt->execute(['id' => $callId]);
    }

    public function rejectCall($callId) {
        $stmt = $this->db()->prepare("UPDATE calls SET status = 'rejected' WHERE id = :id");
        return $stmt->execute(['id' => $callId]);
    }

    public function addCandidate($callId, $type, $candidate) {
        // type: caller or receiver
        $field = $type === 'caller' ? 'caller_candidates' : 'receiver_candidates';
        $stmt = $this->db()->prepare("SELECT $field FROM calls WHERE id = :id");
        $stmt->execute(['id' => $callId]);
        $row = $stmt->fetch();
        
        $candidates = [];
        if ($row && !empty($row[$field])) {
            $candidates = json_decode($row[$field], true) ?: [];
        }
        $candidates[] = $candidate;
        
        $newCandidates = json_encode($candidates);
        $updateStmt = $this->db()->prepare("UPDATE calls SET $field = :candidates WHERE id = :id");
        return $updateStmt->execute(['candidates' => $newCandidates, 'id' => $callId]);
    }
}
