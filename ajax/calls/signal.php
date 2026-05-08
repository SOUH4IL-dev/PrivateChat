<?php
// ajax/calls/signal.php
session_start();
require "../../app/config/db.php";

$db = Database::connect();

$callId = $_POST['call_id'];
$signal = $_POST['signal_data'];

$stmt = $db->prepare("
    INSERT INTO call_signals (call_id, sender_id, signal_data)
    VALUES (?, ?, ?)
");

echo json_encode([
    "success" => $stmt->execute([$callId, $_SESSION['user_id'], $signal])
]);