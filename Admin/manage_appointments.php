<?php
session_start();
include '../config/db.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

// Fetch all appointments
$appointments = $conn->query("SELECT a.id, a.date, a.status, u.name AS patient_name, d.name AS doctor_name
                              FROM appointments a
                              JOIN users u ON a.patient_id = u.id
                              JOIN users d ON a.doctor_id = d.id");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Appointments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h1>Manage Appointments</h1>

    <table class="table">
        <thead>
            <tr>
                <th>Appointment ID</th>
                <th>Patient</th>
                <th>Doctor</th>
                <th>Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($appointment = $appointments->fetch_assoc()): ?>
                <tr>
                    <td><?= $appointment['id'] ?></td>
                    <td><?= $appointment['patient_name'] ?></td>
                    <td><?= $appointment['doctor_name'] ?></td>
                    <td><?= $appointment['date'] ?></td>
                    <td><?= $appointment['status'] ?></td>
                    <td>
                        <a href="approve_appointment.php?id=<?= $appointment['id'] ?>&status=approved" class="btn btn-success">Approve</a>
                        <a href="approve_appointment.php?id=<?= $appointment['id'] ?>&status=declined" class="btn btn-danger">Decline</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
</body>
</html>
