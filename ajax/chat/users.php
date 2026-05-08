<?php
session_start();
require "../../app/config/db.php";

header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit();
}

$db = Database::connect();
$myId = $_SESSION['user_id'];

try {

    $stmt = $db->prepare("
        SELECT 
            u.id,
            u.name,

            /* last message */
            m.message AS last_message,
            m.created_at AS last_time,

            /* unseen count */
            SUM(CASE 
                WHEN m.receiver_id = ? AND m.is_seen = 0 THEN 1 
                ELSE 0 
            END) AS unseen_count

        FROM chats c

        /* get other user */
        JOIN users u 
            ON (u.id = c.user_one AND c.user_two = ?)
            OR (u.id = c.user_two AND c.user_one = ?)

        /* messages */
        LEFT JOIN messages m ON m.chat_id = c.id

        GROUP BY u.id

        ORDER BY last_time DESC
    ");

    $stmt->execute([$myId, $myId, $myId]);

    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($users);

} catch (Exception $e) {
    echo json_encode([
        "error" => $e->getMessage()
    ]);
}