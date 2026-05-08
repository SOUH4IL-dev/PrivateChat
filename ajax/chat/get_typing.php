<?php
session_start();
require "../../app/config/db.php";

header('Content-Type: application/json');

$db = Database::connect();

$me = $_SESSION['user_id'];
$from = $_GET['from'];

$stmt = $db->prepare("
    SELECT is_typing 
    FROM typing_status 
    WHERE sender_id = ? AND receiver_id = ?
");

$stmt->execute([$from, $me]);
$data = $stmt->fetch();

echo json_encode([
    "typing" => $data ? (int)$data['is_typing'] : 0
]);