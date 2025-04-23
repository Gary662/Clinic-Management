<?php
session_start();
include 'config/db.php'; // Adjust path if needed

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);

    if ($name && $email && $message) {
        $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $message);
        $stmt->execute();

        $_SESSION['contact_success'] = "Message sent successfully!";
        header("Location: index.php#contact");
        exit;
    } else {
        $_SESSION['contact_error'] = "Please fill in all fields.";
        header("Location: index.php#contact");
        exit;
    }
}
?>
