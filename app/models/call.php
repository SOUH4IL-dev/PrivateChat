<?php
// app/models/Call.php
class Call {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function createCall($callerId, $receiverId, $type, $roomId) {
        $stmt = $this->db->prepare("
            INSERT INTO calls (caller_id, receiver_id, call_type, room_id)
            VALUES (?, ?, ?, ?)
        ");
        return $stmt->execute([$callerId, $receiverId, $type, $roomId]);
    }

    public function getIncomingCall($userId) {
        $stmt = $this->db->prepare("
            SELECT * FROM calls
            WHERE receiver_id = ?
            AND status = 'ringing'
            ORDER BY id DESC
            LIMIT 1
        ");
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateStatus($callId, $status) {
        $stmt = $this->db->prepare("
            UPDATE calls SET status = ? WHERE id = ?
        ");
        return $stmt->execute([$status, $callId]);
    }

    public function endCall($callId) {
        $stmt = $this->db->prepare("
            UPDATE calls
            SET status = 'ended', ended_at = NOW()
            WHERE id = ?
        ");
        return $stmt->execute([$callId]);
    }
}