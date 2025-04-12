<?php
session_start();
include '../config/db.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'patient') {
    header("Location: ../auth/login.php");
    exit;
}

$patient_id = $_SESSION['user_id'];

// Get patient name
$stmt = $conn->prepare("SELECT name FROM users WHERE id = ?");
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$result = $stmt->get_result();
$patient = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Patient Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="../assets/style.css"> <!-- Your custom styling -->
  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', sans-serif;
    }
    .dashboard-card {
      border-radius: 1rem;
      box-shadow: 0 8px 20px rgba(0,0,0,0.05);
      transition: 0.3s ease;
    }
    .dashboard-card:hover {
      transform: translateY(-5px);
    }
    .welcome {
      font-weight: 600;
      color: #007bff;
    }
    footer {
      background-color: #007bff;
      color: white;
      text-align: center;
      padding: 1rem 0;
      margin-top: 4rem;
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
  <h2 class="welcome mb-4">Welcome, <?php echo htmlspecialchars($patient['name']); ?>!</h2>
  <p class="lead mb-4">Manage your appointments, check your medical history, and update your profile.</p>

  <div class="row g-4">
    <div class="col-md-3">
      <div class="card dashboard-card p-3 text-center">
        <h5>Book Appointment</h5>
        <p class="text-muted">Schedule a new visit.</p>
        <a href="book_appointment.php" class="btn btn-outline-primary btn-sm">Book</a>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card dashboard-card p-3 text-center">
        <h5>Appointments</h5>
        <p class="text-muted">View your upcoming visits.</p>
        <a href="appointments.php" class="btn btn-outline-info btn-sm">View</a>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card dashboard-card p-3 text-center">
        <h5>Medical History</h5>
        <p class="text-muted">Access past consultations.</p>
        <a href="view_history.php" class="btn btn-outline-secondary btn-sm">History</a>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card dashboard-card p-3 text-center">
        <h5>Edit Profile</h5>
        <p class="text-muted">Update personal info.</p>
        <a href="edit_profile.php" class="btn btn-outline-primary btn-sm">Edit</a>
      </div>
    </div>
  </div>
</div>

<footer>
  &copy; <?php echo date("Y"); ?> Fountain Spring Clinic. All rights reserved.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
