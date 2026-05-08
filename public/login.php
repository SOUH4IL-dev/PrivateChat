<?php
session_start();
require "../app/config/db.php";

$error = "";

if (isset($_POST['login'])) {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $db = Database::connect();

    $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {

        session_regenerate_id(true);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];

        header("Location: chat.php");
        exit();

    } else {
        $error = "Invalid email or password";
    }
}
?>

<link rel="stylesheet" href="assets/css/app.css">
<div class="auth-box">

<h2>Login</h2>

<?php if ($error): ?>
    <div class="error"><?= $error ?></div>
<?php endif; ?>

<form method="POST">
    <input name="email" placeholder="Email" required>
    <input name="password" type="password" placeholder="Password" required>

    <button type="submit" name="login">Login</button>
</form>

<div class="link">
    Don't have account? <a href="register.php">Register</a>
</div>

</div>