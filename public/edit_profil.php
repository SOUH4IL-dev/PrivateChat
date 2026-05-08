<?php
session_start();
require __DIR__ . '/../app/config/db.php';

$db = Database::connect();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];
$success = false;

/* GET USER */
$stmt = $db->prepare("SELECT * FROM users WHERE id = :id");
$stmt->execute(['id' => $userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("User not found");
}

/* UPDATE */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name  = trim($_POST['name']);
    $email = trim($_POST['email']);

    $avatar = $user['avatar'];

    /* UPLOAD */
    if (!empty($_FILES['avatar']['name'])) {

        $file = $_FILES['avatar'];

        $allowed = ['jpg','jpeg','png','webp'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (in_array($ext, $allowed)) {

            $avatar = uniqid() . "." . $ext;

            $uploadDir = __DIR__ . "/../uploads/";
            $path = $uploadDir . $avatar;

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            move_uploaded_file($file['tmp_name'], $path);
        }
    }

    if ($name !== "" && $email !== "") {

        $stmt = $db->prepare("
            UPDATE users 
            SET name = :name,
                email = :email,
                avatar = :avatar
            WHERE id = :id
        ");

        $stmt->execute([
            'name' => $name,
            'email' => $email,
            'avatar' => $avatar,
            'id' => $userId
        ]);

        $success = true;

        header("Location: profile.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Edit Profile</title>

<style>
:root{
    --bg:#0b1220;
    --panel:#0f172a;
    --text:#e2e8f0;
    --primary:#3b82f6;
    --border:#1e293b;
}

*{margin:0;padding:0;box-sizing:border-box}

body{
    font-family:Segoe UI;
    background:var(--bg);
    color:var(--text);
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}

.box{
    width:400px;
    background:var(--panel);
    padding:30px;
    border-radius:18px;
    border:1px solid var(--border);
    text-align:center;
}

.avatar-preview{
    width:90px;
    height:90px;
    border-radius:50%;
    margin:auto;
    overflow:hidden;
    background:var(--primary);
}

.avatar-preview img{
    width:100%;
    height:100%;
    object-fit:cover;
}

input{
    width:100%;
    padding:12px;
    margin:10px 0;
    border-radius:10px;
    border:1px solid var(--border);
    background:#0b1220;
    color:var(--text);
}

button{
    width:100%;
    padding:12px;
    border:none;
    border-radius:10px;
    background:var(--primary);
    color:white;
    cursor:pointer;
}
</style>

</head>
<body>

<div class="box">

    <h1>✏️ Edit Profile</h1>

    <div class="avatar-preview">
        <?php if (!empty($user['avatar'])): ?>
            <img src="../uploads/<?= htmlspecialchars($user['avatar']) ?>">
        <?php endif; ?>
    </div>

    <form method="POST" enctype="multipart/form-data">

        <input type="text" name="name"
               value="<?= htmlspecialchars($user['name']) ?>">

        <input type="email" name="email"
               value="<?= htmlspecialchars($user['email']) ?>">

        <input type="file" name="avatar" accept="image/*">

        <button type="submit">Save</button>
    </form>

    <a href="profile.php" style="color:#94a3b8;display:block;margin-top:10px;">
        ← Back
    </a>

</div>

</body>
</html>