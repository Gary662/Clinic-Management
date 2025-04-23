<?php
session_start();
include '../config/db.php';

// Ensure the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

// Check if appointment ID is provided
if (!isset($_GET['id'])) {
    echo "Appointment ID is required.";
    exit;
}

$appointment_id = $_GET['id'];

// Fetch appointment details
$stmt = $conn->prepare("
    SELECT 
        a.id, a.date, a.status,
        p.name AS patient_name, p.email AS patient_email,
        d.name AS doctor_name, d.email AS doctor_email
    FROM appointments a
    JOIN users p ON a.patient_id = p.id
    JOIN users d ON a.doctor_id = d.id
    WHERE a.id = ?
");
$stmt->bind_param("i", $appointment_id);
$stmt->execute();
$result = $stmt->get_result();
$appointment = $result->fetch_assoc();

// Check if appointment exists
if (!$appointment) {
    echo "Appointment not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Appointment Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h1>Appointment Details</h1>

    <table class="table table-bordered">
        <tr>
            <th>Appointment ID</th>
            <td><?= $appointment['id'] ?></td>
        </tr>
        <tr>
            <th>Date</th>
            <td><?= date('Y-m-d', strtotime($appointment['date'])) ?></td>
            </tr>
        <tr>
            <th>Status</th>
            <td><?= ucfirst($appointment['status']) ?></td>
        </tr>
        <tr>
            <th>Patient Name</th>
            <td><?= htmlspecialchars($appointment['patient_name']) ?></td>
        </tr>
        <tr>
            <th>Patient Email</th>
            <td><?= htmlspecialchars($appointment['patient_email']) ?></td>
        </tr>
        <tr>
            <th>Doctor Name</th>
            <td><?= htmlspecialchars($appointment['doctor_name']) ?></td>
        </tr>
        <tr>
            <th>Doctor Email</th>
            <td><?= htmlspecialchars($appointment['doctor_email']) ?></td>
        </tr>
    </table>

    <!-- Action Buttons -->
    <div>
        <!-- Reschedule -->
        <a href="reschedule_appointment.php?id=<?= $appointment['id'] ?>" class="btn btn-info">Reschedule</a>
        
        <!-- Delete -->
        <form action="delete_appointment.php" method="POST" class="d-inline">
            <input type="hidden" name="appointment_id" value="<?= $appointment['id'] ?>">
            <button type="submit" class="btn btn-danger">Delete</button>
        </form>
    </div>

    <br>
    <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
</body>
</html>
