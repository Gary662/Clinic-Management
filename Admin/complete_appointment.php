<?php
session_start();
include '../config/db.php';

$appointment_id = $_POST['appointment_id'] ?? $_GET['id'] ?? null;

if ($appointment_id) {
    $stmt = $conn->prepare("UPDATE appointments SET status = 'completed' WHERE id = ?");
    $stmt->bind_param("i", $appointment_id);
    $stmt->execute();

    header("Location: view_appointment_details.php?id=" . $appointment_id);
    exit;
} else {
    echo "Appointment ID missing.";
}
?>
