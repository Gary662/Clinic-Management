<?php
session_start();
include '../config/db.php';

$doctor_id = $_SESSION['user_id'];
$error = "";
$success = "";

// Fetch the doctor's current details
$stmt = $conn->prepare("SELECT name, email, phone, specialty, address, profile_picture, password FROM users WHERE id = ?");
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$result = $stmt->get_result();
$doctor = $result->fetch_assoc();

// Update doctor profile
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $specialty = $_POST['specialty'] ?? '';
    $address = $_POST['address'] ?? '';
    $profile_picture = $doctor['profile_picture']; // default to current

    // Handle file upload
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $filename = basename($_FILES['profile_picture']['name']);
        $target_file = $upload_dir . $filename;

        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file)) {
            $profile_picture = $target_file;
        } else {
            $error = "Error uploading profile picture.";
        }
    }

    // Hash new password only if it's set
    $hashed_password = !empty($password)
        ? password_hash($password, PASSWORD_DEFAULT)
        : $doctor['password'];

    if (empty($error)) {
        // Update the doctor data in the database
        $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, password = ?, phone = ?, specialty = ?, address = ?, profile_picture = ? WHERE id = ?");
        $stmt->bind_param("sssssssi", $name, $email, $hashed_password, $phone, $specialty, $address, $profile_picture, $doctor_id);
        $stmt->execute();

        $success = "Profile updated successfully!";

        // Refresh doctor data
        $stmt = $conn->prepare("SELECT name, email, phone, specialty, address, profile_picture, password FROM users WHERE id = ?");
        $stmt->bind_param("i", $doctor_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $doctor = $result->fetch_assoc();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h1>Edit Profile</h1>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Name:</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($doctor['name']) ?>" required>
        </div>

        <div class="mb-3">
            <label>Email:</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($doctor['email']) ?>" required>
        </div>

        <div class="mb-3">
            <label>Password (leave blank to keep current):</label>
            <input type="password" name="password" class="form-control">
        </div>

        <div class="mb-3">
            <label>Phone Number:</label>
            <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($doctor['phone']) ?>" required>
        </div>

        <div class="mb-3">
            <label>Specialization:</label>
            <input type="text" name="specialty" class="form-control" value="<?= htmlspecialchars($doctor['specialty']) ?>" required>
        </div>

        <div class="mb-3">
            <label>Address:</label>
            <textarea name="address" class="form-control" required><?= htmlspecialchars($doctor['address']) ?></textarea>
        </div>

        <div class="mb-3">
            <label>Profile Picture:</label>
            <input type="file" name="profile_picture" class="form-control">
            <?php if (!empty($doctor['profile_picture'])): ?>
                <div class="mt-2">
                    <img src="<?= htmlspecialchars($doctor['profile_picture']) ?>" alt="Profile Picture" width="100">
                </div>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-primary">Update Profile</button>
    </form>

    <a href="dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
</body>
</html>
