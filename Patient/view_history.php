<?php
session_start();
include '../config/db.php';

$patient_id = $_SESSION["user_id"];
$result = $conn->query("SELECT m.visit_date, m.notes, u.name AS doctor 
                        FROM medical_history m 
                        JOIN users u ON m.doctor_id = u.id 
                        WHERE m.patient_id = $patient_id");

while ($row = $result->fetch_assoc()) {
    echo "Visited Dr. " . $row["doctor"] . " on " . $row["visit_date"] . ": " . $row["notes"] . "<br>";
}
?>
