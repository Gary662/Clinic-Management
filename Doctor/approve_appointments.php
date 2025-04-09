<?php
session_start();
include '../config/db.php';

// Check if the user is logged in as a doctor
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'doctor') {
    header("Location: ../auth/login.php");
    exit;
}

$doctor_id = $_SESSION['user_id'];

// Get all pending appointments for the logged-in doctor
$query = "
    SELECT a.id, a.date, a.status, u.name AS patient_name 
    FROM appointments a 
    JOIN users u ON a.patient_id = u.id
    WHERE a.doctor_id = ? AND a.status = 'pending'
    ORDER BY a.date DESC
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$result = $stmt->get_result();

// Handle appointment approval or decline
if (isset($_GET['id']) && isset($_GET['status'])) {
    $appointment_id = $_GET['id'];
    $status = $_GET['status'];

    // Update appointment status to 'approved' or 'declined'
    $update_query = "UPDATE appointments SET status = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("si", $status, $appointment_id);
    $update_stmt->execute();

    // Redirect to the same page after action
    header("Location: approve_appointments.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approve Appointments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h1>Approve or Decline Appointments</h1>

    <a href="dashboard.php" class="btn btn-secondary mb-3">Back to Dashboard</a>

    <?php if ($result->num_rows > 0): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Appointment ID</th>
                    <th>Patient</th>
                    <th>Appointment Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['patient_name']) ?></td>
                        <td><?= date('Y-m-d H:i', strtotime($row['date'])) ?></td>
                        <td><?= ucfirst($row['status']) ?></td>
                        <td>
                            <a href="approve_appointments.php?id=<?= $row['id'] ?>&status=approved" class="btn btn-success">Approve</a>
                            <a href="approve_appointments.php?id=<?= $row['id'] ?>&status=declined" class="btn btn-danger">Decline</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info">No pending appointments to approve.</div>
    <?php endif; ?>
</body>
</html>
