<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    $user_id = intval($_POST['user_id']);

    // Check user role
    $stmt = $conn->prepare("SELECT role FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($role);
    $stmt->fetch();
    $stmt->close();

    if ($role === 'admin') {
        header("Location: manage_users.php?error=cannot_delete_admin");
        exit;
    }

    // Delete related appointments first
    $deleteAppointments = $conn->prepare("DELETE FROM appointments WHERE doctor_id = ? OR patient_id = ?");
    $deleteAppointments->bind_param("ii", $user_id, $user_id);
    $deleteAppointments->execute();
    $deleteAppointments->close();

    // Now delete the user
    $deleteUser = $conn->prepare("DELETE FROM users WHERE id = ?");
    $deleteUser->bind_param("i", $user_id);

    if ($deleteUser->execute()) {
        header("Location: manage_users.php?deleted=true");
    } else {
        header("Location: manage_users.php?error=deletion_failed");
    }

    $deleteUser->close();
} else {
    header("Location: manage_users.php");
    exit;
}
