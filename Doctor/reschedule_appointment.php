<?php
session_start();
include '../config/db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

// Get the doctor's ID
$doctor_id = $_SESSION['user_id'];

// Check if the appointment ID is passed in the URL
if (isset($_GET['id'])) {
    $appointment_id = $_GET['id'];

    // Get the appointment details
    $stmt = $conn->prepare("SELECT a.id, a.date, a.status, u.name AS patient_name, d.name AS doctor_name
                            FROM appointments a
                            JOIN users u ON a.patient_id = u.id
                            JOIN users d ON a.doctor_id = d.id
                            WHERE a.id = ? AND a.doctor_id = ?");
    $stmt->bind_param("ii", $appointment_id, $doctor_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        // Appointment not found or not assigned to the doctor
        header("Location: view_appointments.php");
        exit;
    }

    $appointment = $result->fetch_assoc();

    // Handle form submission for rescheduling
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $new_date = $_POST['new_date'];

        // Update the appointment date
        $stmt = $conn->prepare("UPDATE appointments SET date = ? WHERE id = ?");
        $stmt->bind_param("si", $new_date, $appointment_id);
        $stmt->execute();

        // Redirect to the appointments view page
        header("Location: view_appointments.php");
        exit;
    }
} else {
    // No appointment ID passed, redirect to appointments page
    header("Location: view_appointments.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reschedule Appointment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h1>Reschedule Appointment</h1>

    <p><strong>Patient:</strong> <?= $appointment['patient_name'] ?></p>
    <p><strong>Current Appointment Date:</strong> <?= $appointment['date'] ?></p>

    <form method="POST">
        <div class="mb-3">
            <label for="new_date">New Appointment Date:</label>
            <input type="datetime-local" name="new_date" id="new_date" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Reschedule Appointment</button>
        <a href="view_appointments.php" class="btn btn-secondary">Cancel</a>
    </form>
</body>
</html>
