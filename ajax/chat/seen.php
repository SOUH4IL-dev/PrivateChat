<?php
session_start();
require "../../app/config/db.php";

header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit();
}

$db = Database::connect();

$myId = $_SESSION['user_id'];
$chatId = $_POST['chat_id'] ?? null;

if (!$chatId) {
    echo json_encode(["status" => "error", "message" => "chat_id required"]);
    exit();
}

try {

    // ✔️ mark messages as seen (only messages NOT sent by me)
    $stmt = $db->prepare("
        UPDATE messages 
        SET is_seen = 1,
            seen_at = NOW()
        WHERE chat_id = ?
        AND sender_id != ?
        AND is_seen = 0
    ");

    $stmt->execute([$chatId, $myId]);

    echo json_encode([
        "status" => "success",
        "message" => "Messages marked as seen"
    ]);

} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}