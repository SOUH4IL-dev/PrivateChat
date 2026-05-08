<?php
session_start();
require "../../app/config/db.php";

$db = Database::connect();

$user_id = $_SESSION['user_id'];
$status = $_POST['status']; // 1 = online, 0 = offline

$stmt = $db->prepare("
    UPDATE users 
    SET is_online = ?, last_seen = NOW()
    WHERE id = ?
");

$stmt->execute([$status, $user_id]);

echo json_encode(["success" => true]);