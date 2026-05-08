<?php
require "../../app/config/db.php";

$db = Database::connect();

$stmt = $db->query("SELECT id, is_online FROM users");
$data = $stmt->fetchAll();

echo json_encode($data);