<?php
session_start();
include '../config/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

// Ensure appointment ID is provided
if (!isset($_GET['id'])) {
    echo "Appointment ID missing.";
    exit;
}

$appointment_id = $_GET['id'];

// Fetch appointment details (optional)
$stmt = $conn->prepare("SELECT a.id, a.date, u.name AS patient_name 
                        FROM appointments a 
                        JOIN users u ON a.patient_id = u.id 
                        WHERE a.id = ?");
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
    <title>Add Diagnosis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Add Diagnosis for <?= htmlspecialchars($appointment['patient_name']) ?> (<?= date('Y-m-d H:i', strtotime($appointment['date'])) ?>)</h2>

    <form action="add_diagnosis_handler.php" method="POST">
        <input type="hidden" name="appointment_id" value="<?= $appointment['id'] ?>">
        
        <div class="mb-3">
            <label for="diagnosis" class="form-label">Diagnosis</label>
            <textarea class="form-control" name="diagnosis" id="diagnosis" rows="5" required></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Submit Diagnosis</button>
        <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
    </form>
</body>
</html>
