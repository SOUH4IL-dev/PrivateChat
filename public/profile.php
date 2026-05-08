<?php
session_start();
require __DIR__ . '/../app/config/db.php';

$db = Database::connect();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];

/* GET USER */
$stmt = $db->prepare("SELECT * FROM users WHERE id = :id");
$stmt->execute(['id' => $userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("User not found");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Profile</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
:root{
    --bg:#0b1220;
    --panel:#0f172a;
    --text:#e2e8f0;
    --muted:#94a3b8;
    --primary:#3b82f6;
    --border:#1e293b;
    --red:#ef4444;
}

*{margin:0;padding:0;box-sizing:border-box}

body{
    font-family:Segoe UI;
    background:var(--bg);
    color:var(--text);
}

/* APP WRAPPER */
.app{
    display:flex;
}

/* SIDEBAR */
.sidebar{
    width:70px;
    height:100vh;
    background:var(--panel);
    border-right:1px solid var(--border);
    display:flex;
    flex-direction:column;
    align-items:center;
    padding-top:20px;
    gap:25px;
    position:fixed;
    left:0;
    top:0;
}

.sidebar i{
    font-size:18px;
    color:var(--muted);
    cursor:pointer;
    transition:0.2s;
}

.sidebar i:hover{
    color:var(--primary);
    transform:scale(1.2);
}

.sidebar i.active{
    color:var(--primary);
}

/* PROFILE CARD */
.card{
    width:420px;
    background:var(--panel);
    border:1px solid var(--border);
    border-radius:18px;
    padding:30px;
    text-align:center;
    margin:80px auto;
    margin-left:120px;
}

.avatar{
    width:90px;
    height:90px;
    border-radius:50%;
    background:var(--primary);
    display:flex;
    align-items:center;
    justify-content:center;
    margin:auto;
    overflow:hidden;
    font-size:40px;
    font-weight:bold;
    color:white;
}

.avatar img{
    width:100%;
    height:100%;
    object-fit:cover;
}

h2{margin-top:15px}

.info{
    margin:20px 0;
    text-align:left;
    border-top:1px solid var(--border);
    border-bottom:1px solid var(--border);
    padding:15px 0;
}

.info p{
    margin:10px 0;
    color:var(--muted);
}

.info span{
    color:var(--text);
    font-weight:bold;
}

.btn{
    display:block;
    padding:12px;
    border-radius:12px;
    text-decoration:none;
    margin:10px 0;
}

.btn-edit{background:var(--primary);color:white}
.btn-logout{background:var(--red);color:white}
</style>

</head>

<body>

<div class="app">

    <!-- SIDEBAR -->
    <div class="sidebar">
        <i class="fa-solid fa-message" onclick="location.href='chat.php'"></i>
        <i class="fa-solid fa-phone" onclick="location.href='calls.php'"></i>
        <i class="fa-solid fa-user active" onclick="location.href='profile.php'"></i>
        <i class="fa-solid fa-gear" onclick="location.href='settings.php'"></i>
    </div>

    <!-- PROFILE CARD -->
    <div class="card">

        <div class="avatar">
            <?php if (!empty($user['avatar'])): ?>
                <img src="../uploads/<?= htmlspecialchars($user['avatar']) ?>">
            <?php else: ?>
                <?= strtoupper(substr($user['name'],0,1)) ?>
            <?php endif; ?>
        </div>

        <h2><?= htmlspecialchars($user['name']) ?></h2>

        <div class="info">
            <p>👤 <span>Name:</span> <?= htmlspecialchars($user['name']) ?></p>
            <p>✉️ <span>Email:</span> <?= htmlspecialchars($user['email']) ?></p>
        </div>

        <a href="edit_profil.php" class="btn btn-edit">✏️ Edit Profile</a>
        <a href="logout.php" class="btn btn-logout">⏻ Logout</a>

    </div>

</div>

</body>
</html>