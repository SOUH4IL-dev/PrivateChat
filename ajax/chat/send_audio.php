<?php
session_start();
require "../../app/config/db.php";

$db = Database::connect();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error"]);
    exit;
}

$sender = $_SESSION['user_id'];
$receiver = $_POST['receiver_id'];

if (!isset($_FILES['audio'])) {
    echo json_encode(["status" => "error"]);
    exit;
}

$file = $_FILES['audio'];

$dir = "../../uploads/audio/";

if (!is_dir($dir)) {
    mkdir($dir, 0777, true);
}

$name = uniqid() . ".webm";
$path = $dir . $name;

move_uploaded_file($file['tmp_name'], $path);

$stmt = $db->prepare("
    INSERT INTO messages (sender_id, receiver_id, file_path, file_type)
    VALUES (?, ?, ?, ?)
");

$stmt->execute([
    $sender,
    $receiver,
    "uploads/audio/" . $name,
    "audio/webm"
]);

echo json_encode(["status" => "success"]);