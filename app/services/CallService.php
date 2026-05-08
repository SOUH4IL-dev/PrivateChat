<?php
// app/services/CallService.php
require_once __DIR__ . '/../models/Call.php';

class CallService {
    private Call $callModel;

    public function __construct(PDO $db) {
        $this->callModel = new Call($db);
    }

    public function start($callerId, $receiverId, $type) {
        $roomId = uniqid("call_");
        $this->callModel->createCall($callerId, $receiverId, $type, $roomId);
        return $roomId;
    }

    public function answer($callId) {
        return $this->callModel->updateStatus($callId, "accepted");
    }

    public function reject($callId) {
        return $this->callModel->updateStatus($callId, "rejected");
    }

    public function end($callId) {
        return $this->callModel->endCall($callId);
    }
}