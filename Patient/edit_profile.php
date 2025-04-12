<?php
session_start();
include '../config/db.php';

$patient_id = $_SESSION['user_id'];
$error = "";

// Fetch current patient details
$stmt = $conn->prepare("SELECT name, email, gender, phone, dob, address, health_care_number, age FROM users WHERE id = ?");
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$result = $stmt->get_result();
$patient = $result->fetch_assoc();

// Update patient profile
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $gender = $_POST['gender'];
    $phone = $_POST['phone'];
    $dob = $_POST['date_of_birth'];
    $address = $_POST['address'];
    $health_care_number = $_POST['health_care_number'];
    $age = !empty($_POST['age']) ? intval($_POST['age']) : null;

    // Prepare query and binding
    $update_query = "UPDATE users SET name = ?, email = ?, gender = ?, phone = ?, dob = ?, address = ?, health_care_number = ?, age = ?";
    $params = [$name, $email, $gender, $phone, $dob, $address, $health_care_number, $age];
    $types = "sssssssi";

    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $update_query .= ", password = ?";
        $params[] = $hashed_password;
        $types .= "s";
    }

    $update_query .= " WHERE id = ?";
    $params[] = $patient_id;
    $types .= "i";

    // Execute
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();

    $success = "Profile updated successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h1>Edit Profile</h1>

    <?php if (isset($success)): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label>Name:</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($patient['name']) ?>" required>
        </div>

        <div class="mb-3">
            <label>Email:</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($patient['email']) ?>" required>
        </div>

        <div class="mb-3">
            <label>Phone:</label>
            <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($patient['phone'] ?? '') ?>">
        </div>

        <div class="mb-3">
            <label>Gender:</label>
            <select name="gender" class="form-control">
                <option value="">Select</option>
                <option value="Male" <?= ($patient['gender'] == 'Male') ? 'selected' : '' ?>>Male</option>
                <option value="Female" <?= ($patient['gender'] == 'Female') ? 'selected' : '' ?>>Female</option>
                <option value="Other" <?= ($patient['gender'] == 'Other') ? 'selected' : '' ?>>Other</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Date of Birth:</label>
            <input type="date" name="date_of_birth" class="form-control" value="<?= htmlspecialchars($patient['dob'] ?? '') ?>">
        </div>

        <div class="mb-3">
            <label>Age:</label>
            <input type="number" name="age" class="form-control" value="<?= htmlspecialchars($patient['age'] ?? '') ?>">
        </div>

        <div class="mb-3">
            <label>Address:</label>
            <textarea name="address" class="form-control"><?= htmlspecialchars($patient['address'] ?? '') ?></textarea>
        </div>

        <div class="mb-3">
            <label>Health Care Number:</label>
            <input type="text" name="health_care_number" class="form-control" value="<?= htmlspecialchars($patient['health_care_number'] ?? '') ?>">
        </div>

        <div class="mb-3">
            <label>New Password (leave blank to keep current):</label>
            <input type="password" name="password" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Update Profile</button>
    </form>

    <a href="dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
</body>
</html>
