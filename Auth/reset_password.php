<?php
session_start();
include '../config/db.php';
include 'config/auth.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $password = $_POST["password"];
    $token = $_POST["token"];  // Assume token is passed via URL or hidden form field

    // Verify token and reset password
    if (verifyToken($token)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        resetPassword($token, $hashedPassword);
        $success = "Password has been reset. You can now log in.";
    } else {
        $error = "Invalid token.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Reset Password</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php elseif (isset($success)): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label>New Password:</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <input type="hidden" name="token" value="<?= $_GET['token'] ?>">

        <button type="submit" class="btn btn-primary">Reset Password</button>
    </form>
</body>
</html>
