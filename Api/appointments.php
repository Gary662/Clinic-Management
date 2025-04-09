<?php
header("Content-Type: application/json");
include '../config/db.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['doctor_id'])) {
            // Get appointments for a specific doctor
            $doctor_id = $_GET['doctor_id'];
            $query = "
                SELECT a.id, a.date, a.status, u.name AS patient_name
                FROM appointments a
                JOIN users u ON a.patient_id = u.id
                WHERE a.doctor_id = ?
                ORDER BY a.date DESC
            ";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $doctor_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $appointments = [];
            while ($row = $result->fetch_assoc()) {
                $appointments[] = $row;
            }
            
            echo json_encode($appointments);
        } elseif (isset($_GET['patient_id'])) {
            // Get appointments for a specific patient
            $patient_id = $_GET['patient_id'];
            $query = "
                SELECT a.id, a.date, a.status, u.name AS doctor_name
                FROM appointments a
                JOIN users u ON a.doctor_id = u.id
                WHERE a.patient_id = ?
                ORDER BY a.date DESC
            ";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $patient_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $appointments = [];
            while ($row = $result->fetch_assoc()) {
                $appointments[] = $row;
            }
            
            echo json_encode($appointments);
        } else {
            echo json_encode(["error" => "Missing parameter (doctor_id or patient_id)"]);
        }
        break;

    case 'POST':
        // Create a new appointment (for patients)
        $patient_id = $_POST['patient_id'];
        $doctor_id = $_POST['doctor_id'];
        $date = $_POST['date'];

        $stmt = $conn->prepare("INSERT INTO appointments (patient_id, doctor_id, date, status) VALUES (?, ?, ?, 'pending')");
        $stmt->bind_param("iis", $patient_id, $doctor_id, $date);
        $stmt->execute();

        echo json_encode(["success" => "Appointment requested"]);
        break;

    case 'PUT':
        // Update an appointment's status (for doctors)
        parse_str(file_get_contents("php://input"), $put_vars);
        $appointment_id = $put_vars['appointment_id'];
        $status = $put_vars['status'];

        $stmt = $conn->prepare("UPDATE appointments SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $appointment_id);
        $stmt->execute();

        echo json_encode(["success" => "Appointment status updated"]);
        break;

    default:
        echo json_encode(["error" => "Method not allowed"]);
        break;
}
?>
