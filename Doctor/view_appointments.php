<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'doctor') {
    header("Location: ../auth/login.php");
    exit;
}

$doctor_id = $_SESSION['user_id'];

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
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Appointments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="container mt-5">
    <h2>Appointments for You</h2>
    <!-- Updated Back Button -->
    <a href="dashboard.php" class="btn btn-secondary mb-3">Back</a>

    <?php if ($result->num_rows > 0): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Patient</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="appointments-list">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr id="appointment-<?= $row['id'] ?>">
                        <td><?= htmlspecialchars($row['patient_name']) ?></td>
                        <td><?= date('Y-m-d H:i', strtotime($row['date'])) ?></td>
                        <td class="status"><?= ucfirst($row['status']) ?></td>
                        <td>
                            <?php if ($row['status'] == 'pending'): ?>
                                <button class="btn btn-success approve-btn" data-appointment-id="<?= $row['id'] ?>">Approve</button>
                            <?php else: ?>
                                <span class="text-muted">Already <?= ucfirst($row['status']) ?></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info">No appointments scheduled.</div>
    <?php endif; ?>
</body>

<script>
$(document).ready(function() {
    $(".approve-btn").click(function() {
        const appointmentId = $(this).data('appointment-id');
        const row = $(this).closest('tr');
        
        $.ajax({
            url: 'approve_appointment.php',
            type: 'POST',
            data: { appointment_id: appointmentId },
            success: function(response) {
                // Update the status dynamically
                row.find('.status').text('Approved');
                row.find('.approve-btn').remove();
                row.find('td:last-child').html('<span class="text-muted">Already Approved</span>');
            },
            error: function() {
                alert('Error approving the appointment.');
            }
        });
    });
});
</script>

</html>
