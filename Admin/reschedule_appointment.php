<?php
session_start();
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['appointment_id']) && isset($_POST['new_date'])) {
    $appointment_id = $_POST['appointment_id'];
    $new_date = $_POST['new_date'];

    // Update appointment date
    $stmt = $conn->prepare("UPDATE appointments SET date = ? WHERE id = ?");
    $stmt->bind_param("si", $new_date, $appointment_id);
    $stmt->execute();

    header("Location: view_appointment_details.php?id=" . $appointment_id);
    exit;
}
?>

<!-- Frontend for Reschedule Form -->
<form method="POST">
    <label>New Appointment Date:</label>
    <input type="datetime-local" name="new_date" class="form-control" required>
    <input type="hidden" name="appointment_id" value="<?= $appointment['id'] ?>">
    <button type="submit" class="btn btn-primary mt-2">Reschedule</button>
</form>
