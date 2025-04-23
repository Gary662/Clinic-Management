<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'patient') {
    header("Location: ../auth/login.php");
    exit;
}

$patient_id = $_SESSION['user_id'];

// $query = "
//     SELECT a.appointment_datetime, a.status, u.name AS doctor_name, mh.notes
//     FROM appointments a
//     JOIN users u ON a.doctor_id = u.id
//     LEFT JOIN medical_history mh 
//         ON a.patient_id = mh.patient_id 
//         AND 
//     WHERE a.patient_id = ?
//     ORDER BY a.date DESC
// ";
$query = "
SELECT 
    a.appointment_datetime, 
    a.status, 
    u.name AS doctor_name, 
    mh.notes
FROM 
    appointments a
JOIN 
    users u ON a.doctor_id = u.id
LEFT JOIN 
    medical_history mh ON mh.patient_id = a.patient_id 
WHERE 
    a.patient_id = ?
ORDER BY 
    a.appointment_datetime DESC

";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Medical History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Your Medical History</h2>
    <a href="../Patient/dashboard.php" class="btn btn-secondary mb-3">Back</a>


    <?php if ($result->num_rows > 0): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Doctor</th>
                    <th>Status</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= date('Y-m-d H:i', strtotime($row['appointment_datetime'])) ?></td>
                        <td><?= htmlspecialchars($row['doctor_name']) ?></td>
                        <td><?= ucfirst($row['status']) ?></td>
                        <td><?= $row['notes'] ? htmlspecialchars($row['notes']) : '—' ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info">No appointment history found.</div>
    <?php endif; ?>
</body>
</html>
