<?php
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/style.css"> <!-- Custom styling -->
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
        }
        .form-container {
            background-color: white;
            border-radius: 1rem;
            box-shadow: 0 8px 20px rgba(0,0,0,0.05);
            padding: 3rem;
        }
        footer {
            background-color: #007bff;
            color: white;
            text-align: center;
            padding: 1rem 0;
            margin-top: 3rem;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="#">Fountain Spring Clinic</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="book_appointment.php">Book</a></li>
                <li class="nav-item"><a class="nav-link" href="appointments.php">Appointments</a></li>
                <li class="nav-item"><a class="nav-link" href="view_history.php">History</a></li>
                <li class="nav-item"><a class="nav-link" href="edit_profile.php">Profile</a></li>
                <li class="nav-item"><a class="nav-link btn btn-danger text-white ms-2" href="../auth/logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Main Content -->
<div class="container mt-5">
    <h2 class="text-center mb-4">Book an Appointment</h2>

    <?php if (isset($success)): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php elseif (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <div class="form-container">
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Full Name:</label>
                <input type="text" name="full_name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Phone Number:</label>
                <input type="text" name="phone" class="form-control" required>
            </div>

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

            <div class="mb-3">
                <label class="form-label">Date of Birth:</label>
                <input type="date" name="dob" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Choose Doctor:</label>
                <select name="doctor_id" class="form-control" required>
                    <option value="">Select a Doctor</option>
                    <?php while ($doctor = $doctors->fetch_assoc()): ?>
                        <option value="<?= $doctor['id'] ?>"><?= htmlspecialchars($doctor['name']) ?> (Specialty: <?= htmlspecialchars($doctor['specialty']) ?>)</option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Preferred Appointment Date:</label>
                <input type="date" name="date" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Book</button>
            <a href="../Patient/dashboard.php" class="btn btn-secondary">Back</a>
        </form>
    </div>
</div>

<!-- Footer -->
<footer>
    &copy; <?= date("Y"); ?> Fountain Spring Clinic. All rights reserved.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
