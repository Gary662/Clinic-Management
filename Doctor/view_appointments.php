<?php
session_start();
include '../config/db.php';

$doctor_id = $_SESSION["user_id"];
$result = $conn->query("SELECT a.id, u.name AS patient, a.date, a.status 
                        FROM appointments a 
                        JOIN users u ON a.patient_id = u.id 
                        WHERE a.doctor_id = $doctor_id");

while ($row = $result->fetch_assoc()) {
    echo "Appointment with " . $row["patient"] . " on " . $row["date"] . " (Status: " . $row["status"] . ")<br>";
}
?>
