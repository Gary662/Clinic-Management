<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'doctor') {
    header("Location: ../auth/login.php");
    exit;
}

if (!isset($_GET['appointment_id'])) {
    echo "Appointment ID missing.";
    exit;
}

$appointment_id = $_GET['appointment_id'];

$stmt = $conn->prepare("
    SELECT u.name, u.email, u.gender, u.phone, u.dob, u.address
    FROM appointments a
    JOIN users u ON a.patient_id = u.id
    WHERE a.id = ?
");
$stmt->bind_param("i", $appointment_id);
$stmt->execute();
$result = $stmt->get_result();
$patient = $result->fetch_assoc();

if (!$patient) {
    echo "Patient not found.";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Patient Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Patient Information</h2>
    <a href="view_appointments.php" class="btn btn-secondary mb-3">← Back</a>

    <ul class="list-group">
        <li class="list-group-item"><strong>Name:</strong> <?= htmlspecialchars($patient['name']) ?></li>
        <li class="list-group-item"><strong>Email:</strong> <?= htmlspecialchars($patient['email']) ?></li>
        <li class="list-group-item"><strong>Gender:</strong> <?= htmlspecialchars($patient['gender'] ?? 'N/A') ?></li>
        <li class="list-group-item"><strong>Phone:</strong> <?= htmlspecialchars($patient['phone'] ?? 'N/A') ?></li>
        <li class="list-group-item"><strong>Date of Birth:</strong> <?= htmlspecialchars($patient['dob'] ?? 'N/A') ?></li>
        <li class="list-group-item"><strong>Address:</strong> <?= htmlspecialchars($patient['address'] ?? 'N/A') ?></li>
    </ul>
</body>
</html>
