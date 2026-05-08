
<?php

session_start();
require "../../app/config/db.php";

header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit();
}

$db = Database::connect();

$q = $_GET['q'] ?? '';

$stmt = $db->prepare("
    SELECT id, name 
    FROM users 
    WHERE name LIKE ?
    LIMIT 10
");

$stmt->execute(["%$q%"]);

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC)); 



