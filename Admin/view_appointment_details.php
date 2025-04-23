<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

if (!isset($_GET['id'])) {
    echo "Appointment ID is required.";
    exit;
}

$appointment_id = $_GET['id'];

// Fetch appointment details with medical notes and diagnosis
// $stmt = $conn->prepare("
//     SELECT 
//         a.id, a.date, a.status,
//         p.name AS patient_name, p.email AS patient_email,
//         d.name AS doctor_name, d.email AS doctor_email,
//         mh.notes AS medical_notes,
//         mh.diagnosis AS medical_diagnosis
//     FROM appointments a
//     JOIN users p ON a.patient_id = p.id
//     JOIN users d ON a.doctor_id = d.id
//     LEFT JOIN medical_history mh 
//         ON mh.patient_id = a.patient_id 
//         AND mh.doctor_id = a.doctor_id 
//         AND DATE(mh.visit_date) = DATE(a.date)
//     WHERE a.id = ?
// ");
$stmt = $conn->prepare("
    SELECT 
        a.id, a.date, a.status,
        p.name AS patient_name, p.email AS patient_email,
        d.name AS doctor_name, d.email AS doctor_email,
        mh.notes AS medical_notes,
        mh.diagnosis AS medical_diagnosis
    FROM appointments a
    JOIN users p ON a.patient_id = p.id
    JOIN users d ON a.doctor_id = d.id
    LEFT JOIN medical_history mh 
        ON mh.appointment_id = a.id
    WHERE a.id = ?
");

$stmt->bind_param("i", $appointment_id);
$stmt->execute();
$result = $stmt->get_result();
$appointment = $result->fetch_assoc();

if (!$appointment) {
    echo "Appointment not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Appointment Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body class="container mt-5">
    <h1>Appointment Details</h1>

    <table class="table table-bordered">
        <tr>
            <th>Appointment ID</th>
            <td><?= $appointment['id'] ?></td>
        </tr>
        <tr>
            <th>Date</th>
            <td><?= date('Y-m-d', strtotime($appointment['date'])) ?></td>
        </tr>
        <tr>
            <th>Status</th>
            <td>
                <span class="badge bg-<?= $appointment['status'] === 'completed' ? 'success' : ($appointment['status'] === 'pending' ? 'warning' : 'secondary') ?>">
                    <?= ucfirst($appointment['status']) ?>
                </span>
            </td>
        </tr>
        <tr>
            <th>Patient Name</th>
            <td><?= htmlspecialchars($appointment['patient_name']) ?></td>
        </tr>
        <tr>
            <th>Patient Email</th>
            <td><?= htmlspecialchars($appointment['patient_email']) ?></td>
        </tr>
        <tr>
            <th>Doctor Name</th>
            <td><?= htmlspecialchars($appointment['doctor_name']) ?></td>
        </tr>
        <tr>
            <th>Doctor Email</th>
            <td><?= htmlspecialchars($appointment['doctor_email']) ?></td>
        </tr>
        <tr>
            <th>Medical Notes</th>
            <td><?= !empty($appointment['medical_notes']) ? nl2br(htmlspecialchars($appointment['medical_notes'])) : 'No notes available.' ?></td>
        </tr>
        <tr>
            <th>Diagnosis</th>
            <td><?= !empty($appointment['medical_diagnosis']) ? nl2br(htmlspecialchars($appointment['medical_diagnosis'])) : 'No diagnosis available.' ?></td>
        </tr>
    </table>

    <div class="no-print">
        <a href="reschedule_appointment.php?id=<?= $appointment['id'] ?>" class="btn btn-info">Reschedule</a>
        <form action="delete_appointment.php" method="POST" class="d-inline">
            <input type="hidden" name="appointment_id" value="<?= $appointment['id'] ?>">
            <button type="submit" class="btn btn-danger">Delete</button>
        </form>
        <button class="btn btn-outline-primary" onclick="window.print()">Print</button>
        <br><br>
        <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>
</body>
</html>
