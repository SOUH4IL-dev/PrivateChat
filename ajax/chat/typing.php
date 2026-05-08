<?php
session_start();
require "../../app/config/db.php";

header('Content-Type: application/json');

$db = Database::connect();

$sender_id = $_SESSION['user_id'];
$receiver_id = $_POST['receiver_id'];
$is_typing = $_POST['is_typing'];

$stmt = $db->prepare("
    INSERT INTO typing_status (sender_id, receiver_id, is_typing)
    VALUES (?, ?, ?)
    ON DUPLICATE KEY UPDATE is_typing = VALUES(is_typing)
");

$stmt->execute([$sender_id, $receiver_id, $is_typing]);

echo json_encode(["success" => true]);