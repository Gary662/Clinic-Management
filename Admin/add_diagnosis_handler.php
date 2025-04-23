<?php
session_start();
include '../config/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

// Ensure appointment ID and diagnosis are provided
if (!isset($_POST['appointment_id']) || !isset($_POST['diagnosis'])) {
    echo "Appointment ID or Diagnosis missing.";
    exit;
}

$appointment_id = $_POST['appointment_id'];
$diagnosis = $_POST['diagnosis'];

// Insert diagnosis into the medical_history table
$stmt = $conn->prepare("INSERT INTO medical_history (appointment_id, diagnosis) VALUES (?, ?)");
$stmt->bind_param("is", $appointment_id, $diagnosis);
$stmt->execute();

// Check if the insertion was successful
if ($stmt->affected_rows > 0) {
    echo "Diagnosis added successfully.";
} else {
    echo "Failed to add diagnosis.";
}

$stmt->close();
$conn->close();
?>
