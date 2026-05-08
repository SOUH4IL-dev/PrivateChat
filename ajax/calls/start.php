<?php
// ajax/calls/start.php
session_start();
require "../../app/config/db.php";
require "../../app/services/CallService.php";

header("Content-Type: application/json");

$db = Database::connect();
$service = new CallService($db);

$callerId = $_SESSION['user_id'];
$receiverId = $_POST['receiver_id'];
$type = $_POST['type'];

$roomId = $service->start($callerId, $receiverId, $type);

echo json_encode([
    "success" => true,
    "room_id" => $roomId
]);