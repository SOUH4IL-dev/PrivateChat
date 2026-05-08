<?php
session_start();
require "../app/config/db.php";

if (isset($_SESSION['user_id'])) {

    $db = Database::connect();

    $db->prepare("UPDATE users SET status='offline' WHERE id=?")
       ->execute([$_SESSION['user_id']]);
}

// destroy session safely
$_SESSION = [];
session_destroy();

header("Location: login.php");
exit();