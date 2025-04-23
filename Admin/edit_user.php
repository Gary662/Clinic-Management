<?php
session_start();
include '../config/db.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

// Check if user ID is provided
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Fetch the user's details
    $stmt = $conn->prepare("SELECT name, email, role, phone, address, gender FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Handle form submission for updating user
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $role = $_POST['role'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        $gender = $_POST['gender'];
        $password = $_POST['password'];

        // Update user information
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, role = ?, phone = ?, address = ?, gender = ?, password = ? WHERE id = ?");
            $stmt->bind_param("sssssssi", $name, $email, $role, $phone, $address, $gender, $hashed_password, $user_id);
        } else {
            $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, role = ?, phone = ?, address = ?, gender = ? WHERE id = ?");
            $stmt->bind_param("ssssssi", $name, $email, $role, $phone, $address, $gender, $user_id);
        }

        $stmt->execute();
        $success_message = "User updated successfully!";
    }
} else {
    header("Location: manage_users.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h1>Edit User</h1>

    <?php if (isset($success_message)): ?>
        <div class="alert alert-success"><?= $success_message ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label>Name:</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
        </div>

        <div class="mb-3">
            <label>Email:</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
        </div>

        <div class="mb-3">
            <label>Role:</label>
            <select name="role" class="form-control" required>
                <option value="patient" <?= $user['role'] == 'patient' ? 'selected' : '' ?>>Patient</option>
                <option value="doctor" <?= $user['role'] == 'doctor' ? 'selected' : '' ?>>Doctor</option>
                <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Gender:</label>
            <select name="gender" class="form-control" required>
                <option value="male" <?= $user['gender'] == 'male' ? 'selected' : '' ?>>Male</option>
                <option value="female" <?= $user['gender'] == 'female' ? 'selected' : '' ?>>Female</option>
                <option value="other" <?= $user['gender'] == 'other' ? 'selected' : '' ?>>Other</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Phone Number:</label>
            <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone']) ?>" required>
        </div>

        <div class="mb-3">
            <label>Address:</label>
            <textarea name="address" class="form-control" required><?= htmlspecialchars($user['address']) ?></textarea>
        </div>

        <div class="mb-3">
            <label>New Password (leave blank to keep current):</label>
            <input type="password" name="password" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Save Changes</button>
        <a href="manage_users.php" class="btn btn-secondary">Back to Users</a>
    </form>

    <!-- Delete Form -->
    <form method="POST" action="delete_user.php" class="mt-3">
        <input type="hidden" name="user_id" value="<?= $user_id ?>">
        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this user?');">
            Delete User
        </button>
    </form>
</body>
</html>
