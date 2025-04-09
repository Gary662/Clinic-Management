<?php
session_start();
include '../config/db.php';

$error = "";

// Redirect logged-in users to their appropriate dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: ../" . ucfirst($_SESSION['role']) . "/dashboard.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $role = $_POST["role"];

    // Prepare and execute SQL query to check if the user exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the user exists
    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            // Check if the selected role matches the user's role
            if ($user['role'] === $role) {
                $_SESSION["user_id"] = $user["id"];
                $_SESSION["role"] = $user["role"];
                $_SESSION["name"] = $user["name"];

                // Redirect based on the role
                if ($role === "patient") {
                    header("Location: ../Patient/dashboard.php");
                } elseif ($role === "doctor") {
                    header("Location: ../Doctor/dashboard.php");
                } elseif ($role === "admin") {
                    header("Location: ../Admin/dashboard.php");
                }
                exit;
            } else {
                // Debugging: output roles to help identify the issue
                echo "Selected role: " . $role . "<br>";
                echo "User role: " . $user['role'] . "<br>";
                $error = "Role mismatch. Please select the correct role.";
            }
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "User not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2 class="mb-4">Login</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" class="w-50">
        <div class="mb-3">
            <label>Email:</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Password:</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Role:</label>
            <select name="role" class="form-control">
                <option value="patient">Patient</option>
                <option value="doctor">Doctor</option>
                <option value="admin">Admin</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Login</button>
        <a href="../index.php" class="btn btn-secondary">Back to Home</a>
    </form>
</body>
</html>
