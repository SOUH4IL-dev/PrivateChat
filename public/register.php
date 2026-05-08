<?php
session_start();
require "../app/config/db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = htmlspecialchars(trim($_POST['name']));
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if ($name && $email && $password && $confirm) {

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Invalid email format";

        } elseif ($password !== $confirm) {
            $error = "Passwords do not match";

        } elseif (strlen($password) < 6) {
            $error = "Password must be at least 6 characters";

        } else {

            $db = Database::connect();

            $check = $db->prepare("SELECT id FROM users WHERE email=?");
            $check->execute([$email]);

            if ($check->fetch()) {
                $error = "Email already exists";

            } else {

                $hashed = password_hash($password, PASSWORD_DEFAULT);

                $stmt = $db->prepare("
                    INSERT INTO users (name, email, password, status)
                    VALUES (?, ?, ?, 'offline')
                ");

                $stmt->execute([$name, $email, $hashed]);

                header("Location: login.php");
                exit();
            }
        }

    } else {
        $error = "All fields are required";
    }
}
?>

<link rel="stylesheet" href="assets/css/app.css">

<div class="auth-box">
<h2>Register</h2>

<?php if ($error): ?>
    <div class="error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="POST">
    <input name="name" placeholder="Name" required>
    <input name="email" type="email" placeholder="Email" required>
    <input name="password" type="password" placeholder="Password" required>
    <input name="confirm_password" type="password" placeholder="Confirm Password" required>
    <button type="submit">Create account</button>
</form>

<div class="link">
    Already have account? <a href="login.php">Login</a>
</div>
</div>