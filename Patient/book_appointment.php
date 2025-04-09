<?php
include '../config/db.php';
session_start();

// Check if user is logged in and is a patient
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'patient') {
    header("Location: ../auth/login.php");
    exit;
}

// Get list of doctors
$doctors = $conn->query("SELECT id, name FROM users WHERE role = 'doctor'");

// Form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = $_SESSION['user_id'];
    $doctor_id = $_POST['doctor_id'] ?? null;
    $date = $_POST['date'] ?? null;

    if ($doctor_id && $date) {
        $stmt = $conn->prepare("INSERT INTO appointments (patient_id, doctor_id, date, status) VALUES (?, ?, ?, 'pending')");
        $stmt->bind_param("iis", $patient_id, $doctor_id, $date);
        $stmt->execute();
        $success = "Appointment requested!";
    } else {
        $error = "Please select a doctor and pick a date.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Book Appointment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Book an Appointment</h2>

    <?php if (isset($success)): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php elseif (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" class="mt-4">
        <div class="mb-3">
            <label class="form-label">Doctor:</label>
            <select name="doctor_id" class="form-control" required>
                <option value="">Select a doctor</option>
                <?php while ($row = $doctors->fetch_assoc()): ?>
                    <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Date:</label>
            <input type="datetime-local" name="date" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Book</button>
        <a href="../index.php" class="btn btn-secondary">Back</a>
    </form>
</body>
</html>
