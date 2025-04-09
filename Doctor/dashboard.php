<?php
session_start();
include '../config/db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$doctor_id = $_SESSION['user_id'];

// Get the doctor's name
$stmt = $conn->prepare("SELECT name FROM users WHERE id = ?");
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$result = $stmt->get_result();
$doctor = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h1>Welcome, Dr. <?= $doctor['name'] ?>!</h1>
    <p class="lead">Here you can manage your appointments and add medical notes.</p>

    <div>
        <a href="view_appointments.php" class="btn btn-primary mb-3">View Appointments</a>
        <a href="add_note.php" class="btn btn-outline-primary mb-3">Add Medical Note</a>
        <a href="approve_appointments.php" class="btn btn-outline-success mb-3">Approve Appointments</a>
        <a href="edit_profile.php" class="btn btn-outline-primary mb-3">Edit Profile</a>
    </div>

    <!-- Updated Logout Button -->
    <a href="../auth/logout.php" class="btn btn-danger">Logout</a>
</body>
</html>
