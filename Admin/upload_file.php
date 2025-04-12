<?php
session_start();
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file']) && isset($_POST['appointment_id'])) {
    $appointment_id = $_POST['appointment_id'];
    $file = $_FILES['file'];

    // Check for file upload errors
    if ($file['error'] != 0) {
        die("File upload error.");
    }

    // Specify upload directory and generate unique file name
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($file['name']);
    $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Validate file type (optional)
    $allowed_types = ['pdf', 'jpg', 'jpeg', 'png', 'docx', 'txt'];
    if (!in_array($file_type, $allowed_types)) {
        die("Invalid file type.");
    }

    // Move file to the target directory
    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        // Update the appointment with the file path
        $stmt = $conn->prepare("UPDATE appointments SET file_path = ? WHERE id = ?");
        $stmt->bind_param("si", $target_file, $appointment_id);
        $stmt->execute();

        header("Location: view_appointment_details.php?id=" . $appointment_id);
        exit;
    } else {
        die("Error moving uploaded file.");
    }
}
?>
