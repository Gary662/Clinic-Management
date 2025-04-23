<?php
session_start();
include '../config/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

// Check if appointment ID is passed
if (!isset($_GET['id'])) {
    header("Location: view_appointments.php");
    exit;
}

$appointment_id = $_GET['id'];
$error = '';
$success = '';

// Fetch appointment details
$stmt = $conn->prepare("SELECT a.id, a.date, u.name AS patient_name, d.name AS doctor_name
                        FROM appointments a
                        JOIN users u ON a.patient_id = u.id
                        JOIN users d ON a.doctor_id = d.id
                        WHERE a.id = ?");
$stmt->bind_param("i", $appointment_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: view_appointments.php");
    exit;
}

$appointment = $result->fetch_assoc();

// Handle reschedule form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_date'], $_POST['appointment_id'])) {
    $new_date = $_POST['new_date'];
    $appointment_id_post = $_POST['appointment_id'];

    // Prevent past dates
    if (strtotime($new_date) < time()) {
        $error = "Cannot reschedule to a past date.";
    } else {
        $stmt = $conn->prepare("UPDATE appointments SET date = ? WHERE id = ?");
        $stmt->bind_param("si", $new_date, $appointment_id_post);
        if ($stmt->execute()) {
            $success = "Appointment rescheduled successfully.";
            // Refresh to show updated date
            $appointment['date'] = $new_date;
        } else {
            $error = "Failed to reschedule appointment.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reschedule Appointment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h1>Reschedule Appointment</h1>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php elseif (!empty($success)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <p><strong>Patient:</strong> <?= htmlspecialchars($appointment['patient_name']) ?></p>
    <p><strong>Doctor:</strong> <?= htmlspecialchars($appointment['doctor_name']) ?></p>
    <p><strong>Current Date:</strong> <?= htmlspecialchars($appointment['date']) ?></p>

    <form method="POST" class="mt-3">
        <label for="new_date" class="form-label">New Appointment Date:</label>
        <input type="datetime-local" name="new_date" class="form-control" required>

        <input type="hidden" name="appointment_id" value="<?= htmlspecialchars($appointment['id']) ?>">

        <button type="submit" class="btn btn-primary mt-3">Reschedule</button>
        <a href="view_appointments.php" class="btn btn-secondary mt-3">Back</a>
    </form>
</body>
</html>
