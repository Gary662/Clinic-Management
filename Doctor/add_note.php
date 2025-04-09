<?php
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = $_POST['patient_id'];
    $doctor_id = 2; // assume logged-in doctor
    $date = date("Y-m-d H:i:s");
    $notes = $_POST['notes'];

    $stmt = $conn->prepare("INSERT INTO medical_history (patient_id, doctor_id, visit_date, notes) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $patient_id, $doctor_id, $date, $notes);
    $stmt->execute();

    echo "Note added!";
}
?>

<form method="POST">
<h2>Add Visit Note</h2>
    Patient ID: <input type="number" name="patient_id"><br>
    Notes:<br>
    <textarea name="notes"></textarea><br>
    <button type="submit">Add Note</button>
</form>
<a href="../index.php">Back</a>
