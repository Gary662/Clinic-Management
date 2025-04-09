<?php
session_start();
include '../config/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$patient_id = $_SESSION['user_id'];

// Get the patient's name
$stmt = $conn->prepare("SELECT name FROM users WHERE id = ?");
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$result = $stmt->get_result();
$patient = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h1>Welcome, <?= $patient['name'] ?>!</h1>
    <p class="lead">Here you can manage your appointments and view your medical history.</p>

    <div>
        <a href="book_appointment.php" class="btn btn-primary mb-3">Book an Appointment</a>
        <a href="view_history.php" class="btn btn-secondary mb-3">View Medical History</a>
        <a href="edit_profile.php" class="btn btn-outline-primary mb-3">Edit Profile</a>
    </div>

    <a href="../index.php" class="btn btn-danger">Logout</a>
</body>
</html>
