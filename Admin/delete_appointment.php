<?php
session_start();
include '../config/db.php';

// Check if user is an admin
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['appointment_id'])) {
    $appointment_id = $_POST['appointment_id'];

    // Optional: delete related data if foreign keys exist
    // $conn->query("DELETE FROM diagnoses WHERE appointment_id = $appointment_id");

    $stmt = $conn->prepare("DELETE FROM appointments WHERE id = ?");
    $stmt->bind_param("i", $appointment_id);

    if ($stmt->execute()) {
        header("Location: dashboard.php?deleted=true");
        exit;
    } else {
        echo "Error deleting appointment: " . $conn->error;
    }
} else {
    header("Location: dashboard.php");
    exit;
}
