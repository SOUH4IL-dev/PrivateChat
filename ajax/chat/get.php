<?php
session_start();
require "../../app/config/db.php";

header("Content-Type: application/json");

$db = Database::connect();

$me = $_SESSION['user_id'];
$receiver = $_GET['receiver_id'] ?? null;

$stmt = $db->prepare("
SELECT id FROM chats
WHERE (user_one=? AND user_two=?) OR (user_one=? AND user_two=?)
");
$stmt->execute([$me,$receiver,$receiver,$me]);
$chat = $stmt->fetch();

if (!$chat) {
    echo json_encode(["status"=>"success","messages"=>[]]);
    exit();
}

$chatId = $chat['id'];

/* mark seen automatically */
$db->prepare("
UPDATE messages 
SET is_seen=1, seen_at=NOW()
WHERE chat_id=? AND sender_id!=?
")->execute([$chatId,$me]);

/* messages */
$stmt = $db->prepare("
SELECT * FROM messages
WHERE chat_id=?
ORDER BY id ASC
");

$stmt->execute([$chatId]);

echo json_encode([
    "status"=>"success",
    "chat_id"=>$chatId,
    "messages"=>$stmt->fetchAll(PDO::FETCH_ASSOC)
]);