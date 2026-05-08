<?php
// ajax/calls/answer.php
session_start();
require "../../app/config/db.php";
require "../../app/services/CallService.php";

$db = Database::connect();
$service = new CallService($db);

$callId = $_POST['call_id'];

echo json_encode([
    "success" => $service->answer($callId)
]);