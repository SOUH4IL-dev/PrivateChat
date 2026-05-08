<?php
session_start();
require "../../app/config/db.php";

header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {
    exit(json_encode(["status" => "error"]));
}

$db = Database::connect();

$msgId = $_POST['message_id'] ?? null;
$me = $_SESSION['user_id'];

$stmt = $db->prepare("
    UPDATE messages 
    SET deleted = 1 
    WHERE id = ? AND sender_id = ?
");

$stmt->execute([$msgId, $me]);

echo json_encode(["status" => "success"]);