<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
  <title>Spring Fountain Clinic</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
  <div class="text-center">
    <h1 class="mb-4">🏥 Welcome to Spring Fountain Clinic</h1>

    <?php if (!isset($_SESSION['user_id'])): ?>
      <a href="auth/register.php" class="btn btn-success me-2">Register</a>
      <a href="auth/login.php" class="btn btn-primary">Login</a>

    <?php else: ?>
      <p class="lead">You are logged in as <strong><?= ucfirst($_SESSION['role']) ?></strong>.</p>

      <?php if ($_SESSION['role'] === 'patient'): ?>
        <a href="patient/book_appointment.php" class="btn btn-outline-primary me-2">Book Appointment</a>
        <a href="patient/view_history.php" class="btn btn-outline-secondary">View Medical History</a>

      <?php elseif ($_SESSION['role'] === 'doctor'): ?>
        <a href="doctor/view_appointments.php" class="btn btn-outline-primary me-2">View Appointments</a>
        <a href="doctor/add_note.php" class="btn btn-outline-secondary">Add Medical Note</a>
        
      <?php elseif ($_SESSION['role'] === 'admin'): ?>
        <a href="admin/dashboard.php" class="btn btn-outline-primary me-2">Admin Dashboard</a>
      <?php endif; ?>

      <div class="mt-4">
        <a href="auth/logout.php" class="btn btn-danger">Logout</a>
      </div>
    <?php endif; ?>
  </div>
</body>
</html>
