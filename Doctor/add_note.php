<?php
include '../config/db.php';

// Check if the doctor is logged in
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'doctor') {
    header("Location: ../auth/login.php");
    exit;
}

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = $_POST['patient_id'];
    $doctor_id = $_SESSION['user_id']; // use logged-in doctor's ID
    $notes = $_POST['notes'];

    // Basic validation
    if (empty($patient_id) || empty($notes)) {
        $error = "Patient ID and notes are required.";
    } else {
        // Sanitize the inputs (especially for notes)
        $notes = htmlspecialchars($notes);

        // Prepare and execute the SQL query to insert the medical note
        $stmt = $conn->prepare("INSERT INTO medical_history (patient_id, doctor_id, visit_date, notes) VALUES (?, ?, ?, ?)");
        $visit_date = date("Y-m-d H:i:s"); // Current date and time
        $stmt->bind_param("iiss", $patient_id, $doctor_id, $visit_date, $notes);

        if ($stmt->execute()) {
            $success = "Note added successfully!";
        } else {
            $error = "Failed to add note. Please try again later.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Medical Note</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Add Medical Visit Note</h2>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php elseif (isset($success)): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label for="patient_id" class="form-label">Patient ID:</label>
            <input type="number" name="patient_id" id="patient_id" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="notes" class="form-label">Notes:</label>
            <textarea name="notes" id="notes" class="form-control" rows="5" required></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Add Note</button>
    </form>

    <a href="dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
</body>
</html>
