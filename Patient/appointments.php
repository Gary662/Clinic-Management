<?php
session_start();
include '../config/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$patient_id = $_SESSION['user_id'];

// Fetch all the appointments of the patient
$stmt = $conn->prepare("SELECT id, date, doctor_id, status FROM appointments WHERE patient_id = ?");
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$result = $stmt->get_result();

// Handle appointment cancellation
if (isset($_GET['cancel_id'])) {
    $cancel_id = $_GET['cancel_id'];
    $stmt = $conn->prepare("DELETE FROM appointments WHERE id = ?");
    $stmt->bind_param("i", $cancel_id);
    $stmt->execute();
    $cancel_success = "Appointment cancelled successfully.";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h1>Your Appointments</h1>

    <?php if (isset($cancel_success)): ?>
        <div class="alert alert-success"><?= $cancel_success ?></div>
    <?php endif; ?>

    <table class="table">
        <thead>
            <tr>
                <th>Appointment Date</th>
                <th>Doctor</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['date'] ?></td>
                    <td><?= $row['doctor_id'] ?></td> <!-- You can join doctor table for name -->
                    <td><?= $row['status'] ?></td>
                    <td>
                        <?php if ($row['status'] == 'pending'): ?>
                            <a href="?cancel_id=<?= $row['id'] ?>" class="btn btn-danger">Cancel</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
</body>
</html>
