<?php
include '../config/db.php';
session_start();

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Prevent deleting admin
    if ($user_id == $_SESSION['user_id']) {
        header("Location: index.php");
        exit;
    }

    // Delete user
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    header("Location: index.php");
    exit;
}
?>
