<?php
// File: patient/book_appointment.php
session_start();
include '../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'patient') {
    header("Location: ../auth/login.php");
    exit;
}

$specialty_filter = $_GET['specialty'] ?? '';
$query = "SELECT id, name, specialty FROM users WHERE role = 'doctor'";
if ($specialty_filter) {
    $query .= " AND specialty = '" . $conn->real_escape_string($specialty_filter) . "'";
}
$doctors = $conn->query($query);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = $_SESSION['user_id'];
    $doctor_id = $_POST['doctor_id'] ?? null;
    $date = $_POST['date'] ?? null;

    // New fields
    $full_name = $_POST['full_name'] ?? ''; // Full Name
    $phone = $_POST['phone'] ?? '';
    $health_care_number = $_POST['health_care_number'] ?? ''; // Alberta Health Care Number
    $sex = $_POST['sex'] ?? '';
    $age = $_POST['age'] ?? 0;
    $dob = $_POST['dob'] ?? ''; // Date of Birth

    if ($doctor_id && $date && $full_name && $phone && $health_care_number && $sex && $age && $dob) {
        $check = $conn->prepare("SELECT * FROM appointments WHERE patient_id = ? AND doctor_id = ? AND date = ?");
        $check->bind_param("iis", $patient_id, $doctor_id, $date);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            $error = "You already have an appointment at this time.";
        } else {
            $stmt = $conn->prepare("INSERT INTO appointments (patient_id, doctor_id, date, status, full_name, phone, health_care_number, sex, age, dob) 
                                    VALUES (?, ?, ?, 'pending', ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iisssssis", $patient_id, $doctor_id, $date, $full_name, $phone, $health_care_number, $sex, $age, $dob);
            $stmt->execute();
            $success = "Appointment requested!";
        }
    } else {
        $error = "Please fill in all required fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment</title>
    <link rel="stylesheet" href="../assets/css/style.css"> <!-- Link to your centralized CSS -->
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
            <label class="form-label">Full Name:</label>
            <input type="text" name="full_name" class="form-control" required>
        </div>

        <!-- New Patient Details -->
        <div class="mb-3">
            <label class="form-label">Phone Number:</label>
            <input type="text" name="phone" class="form-control" required>
        </div>

        <!-- Alberta Health Care Number -->
        <div class="mb-3">
            <label class="form-label">Alberta Health Care Number:</label>
            <input type="text" name="health_care_number" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Sex:</label>
            <select name="sex" class="form-control" required>
                <option value="">Select...</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Age:</label>
            <input type="number" name="age" class="form-control" min="0" required>
        </div>

        <!-- Date of Birth -->
        <div class="mb-3">
            <label class="form-label">Date of Birth:</label>
            <input type="date" name="dob" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Book</button>
        <a href="../Patient/dashboard.php" class="btn btn-secondary">Back</a>
    </form>
</body>
</html>
