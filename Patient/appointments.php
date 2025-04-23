<?php
session_start();
include '../config/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$patient_id = $_SESSION['user_id'];

// Fetch all the appointments with doctor names and reschedule info
$stmt = $conn->prepare("SELECT a.id, a.date, a.time, a.status, a.updated_at, d.name AS doctor_name 
                        FROM appointments a 
                        JOIN users d ON a.doctor_id = d.id 
                        WHERE a.patient_id = ?");
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$result = $stmt->get_result();

// Check for approved appointments and store all
$approved_found = false;
$appointments = [];

while ($row = $result->fetch_assoc()) {
    if ($row['status'] === 'approved') {
        $approved_found = true;
    }
    $appointments[] = $row;
}

// Handle appointment cancellation
if (isset($_GET['cancel_id'])) {
    $cancel_id = $_GET['cancel_id'];
    $stmt = $conn->prepare("DELETE FROM appointments WHERE id = ?");
    $stmt->bind_param("i", $cancel_id);
    $stmt->execute();
    header("Location: view_appointments.php?cancelled=1");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Appointments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h1 class="mb-4">Your Appointments</h1>

    <?php if (isset($_GET['cancelled'])): ?>
        <div class="alert alert-success">Appointment cancelled successfully.</div>
    <?php endif; ?>

    <?php if ($approved_found): ?>
        <div class="alert alert-success">
            🎉 One or more of your appointments have been <strong>approved</strong>! Please check the list below.
        </div>
    <?php endif; ?>

    <?php if (count($appointments) > 0): ?>
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Appointment Date</th>
                    <th>Appointment Time</th>
                    <th>Doctor</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($appointments as $row): ?>
                    <tr class="<?= $row['status'] === 'approved' ? 'table-success' : '' ?>">
                        <td><?= date('Y-m-d', strtotime($row['date'])) ?></td>
                        <td><?= htmlspecialchars($row['time']) ?></td>
                        <td><?= htmlspecialchars($row['doctor_name']) ?></td>
                        <td>
                            <?php if ($row['status'] === 'approved'): ?>
                                <span class="badge bg-success">Approved</span>
                            <?php elseif ($row['status'] === 'pending'): ?>
                                <span class="badge bg-warning text-dark">Pending</span>
                            <?php else: ?>
                                <span class="badge bg-secondary"><?= ucfirst($row['status']) ?></span>
                            <?php endif; ?>

                            <?php if (!empty($row['updated_at'])): ?>
                                <span class="badge bg-info text-dark ms-1">Rescheduled</span>
                                <small class="text-muted d-block">Updated: <?= date('M j, Y g:i a', strtotime($row['updated_at'])) ?></small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($row['status'] === 'pending'): ?>
                                <a href="?cancel_id=<?= $row['id'] ?>" class="btn btn-danger btn-sm"
                                   onclick="return confirm('Are you sure you want to cancel this appointment?');">
                                    Cancel
                                </a>
                            <?php else: ?>
                                <span class="text-muted">No Action</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No appointments found.</p>
    <?php endif; ?>

    <a href="dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
</body>
</html>
