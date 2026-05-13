<?php
session_start();
require "../app/config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$db = Database::connect();
$myId = $_SESSION['user_id'];

$stmt = $db->prepare("SELECT id, name FROM users WHERE id != ?");
$stmt->execute([$myId]);
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Chat Pro</title>

    <link rel="stylesheet" href="assets/css/chat.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

    <div class="app">

        <!-- ================= NAV ================= -->
        <div class="nav-bar">

            <i class="fa-solid fa-message" onclick="location.href='chat.php'"></i>
            <i class="fa-solid fa-phone" onclick="location.href='calls.php'"></i>
            <i class="fa-solid fa-user" onclick="location.href='profile.php'"></i>
            <!--
<i class="fa-solid fa-gear" onclick="location.href='settings.php'"></i>
-->
        </div>

        <!-- ================= SIDEBAR ================= -->
        <div class="sidebar">

            <div class="sidebar-header">
                <h2>Chats</h2>
            </div>

            <!-- SEARCH -->
            <input type="text" id="search" class="search" placeholder="Search users...">

            <!-- USERS LIST -->
            <div id="users">

                <?php foreach ($users as $u): ?>
                    <div class="user" data-user="<?= $u['id'] ?>"
                        onclick='selectUser({id: <?= $u["id"] ?>, name: "<?= htmlspecialchars($u["name"]) ?>"})'>

                        <div class="avatar">
                            <?= strtoupper(substr($u['name'], 0, 1)) ?>
                        </div>

                        <div class="user-info">
                            <?= htmlspecialchars($u['name']) ?>
                        </div>

                        <!-- ONLINE DOT -->
                        <div class="status-dot" id="status-<?= $u['id'] ?>"></div>

                    </div>
                <?php endforeach; ?>

            </div>

        </div>

        <!-- ================= CHAT ================= -->
        <div class="chat">

            <!-- HEADER -->
            <div class="chat-header">

                <div class="chat-title">
                    <h3 id="chatWith">Select user</h3>
                    <small id="typing"></small>
                </div>

                <!-- CALL ACTIONS -->
                <div class="chat-actions">
                    <i class="fa-solid fa-phone" onclick="startVoiceCall()"></i>
                    <i class="fa-solid fa-video" onclick="startVideoCall()"></i>
                </div>

            </div>

            <!-- MESSAGES -->
            <div id="chat-box"></div>

            <!-- INPUT -->
            <div class="chat-input">

                <!-- TEXT -->
                <input type="text" id="message" placeholder="Type message...">

                <!-- IMAGE -->
                <label class="icon-btn">
                    <i class="fa-solid fa-image"></i>
                    <input type="file" id="imageInput" hidden>
                </label>

                <!-- MIC -->
                <button class="icon-btn" onclick="toggleRecording()">
                    <i class="fa-solid fa-microphone"></i>
                </button>

                <!-- SEND -->
                <button class="send-btn" onclick="sendMessage()">
                    <i class="fa-solid fa-paper-plane"></i>
                </button>

            </div>

        </div>

    </div>

    <script>
        const CURRENT_USER_ID = <?= $myId ?>;
    </script>

    <script src="assets/js/chat.js"></script>

</body>

</html>