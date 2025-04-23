<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $to = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    $headers = "From: clinic@example.com\r\n";
    $headers .= "Reply-To: clinic@example.com\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    if (mail($to, $subject, $message, $headers)) {
        echo "<script>alert('Reply sent successfully!'); window.location.href = 'messages.php';</script>";
    } else {
        echo "<script>alert('Failed to send reply.'); window.location.href = 'messages.php';</script>";
    }
}
?>
