<?php
session_start();
require "../../app/config/db.php";

header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status"=>"error","message"=>"not logged"]);
    exit();
}

$db = Database::connect();

$sender = $_SESSION['user_id'];
$receiver = $_POST['receiver_id'] ?? null;
$message = trim($_POST['message'] ?? "");

/* VALIDATION */
if (!$receiver || !$message) {
    echo json_encode(["status"=>"error","message"=>"data missing"]);
    exit();
}

try {

    // 1. GET OR CREATE CHAT
    $stmt = $db->prepare("
        SELECT id FROM chats
        WHERE (user_one=? AND user_two=?)
           OR (user_one=? AND user_two=?)
        LIMIT 1
    ");

    $stmt->execute([$sender,$receiver,$receiver,$sender]);
    $chat = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$chat) {
        $stmt = $db->prepare("INSERT INTO chats (user_one,user_two) VALUES (?,?)");
        $stmt->execute([$sender,$receiver]);
        $chatId = $db->lastInsertId();
    } else {
        $chatId = $chat['id'];
    }

    // 2. INSERT MESSAGE
    $stmt = $db->prepare("
        INSERT INTO messages (chat_id,sender_id,message,created_at)
        VALUES (?,?,?,NOW())
    ");

    $stmt->execute([$chatId,$sender,$message]);

    echo json_encode([
        "status"=>"success",
        "chat_id"=>$chatId
    ]);

} catch(Exception $e) {
    echo json_encode([
        "status"=>"error",
        "message"=>$e->getMessage()
    ]);
}