<?php
include '../config/db.php';
session_start();

// Check if user is admin
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

// Get users
$users = $conn->query("SELECT id, name, email, role FROM users");

// Get all appointments
$appointments = $conn->query("SELECT a.id, a.date, a.status, u.name AS patient_name, d.name AS doctor_name 
                              FROM appointments a 
                              JOIN users u ON a.patient_id = u.id
                              JOIN users d ON a.doctor_id = d.id");

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
  <h1>Admin Dashboard</h1>

  <!-- Users List -->
  <h2>Users</h2>
  <table class="table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Role</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($user = $users->fetch_assoc()): ?>
        <tr>
          <td><?= $user['id'] ?></td>
          <td><?= $user['name'] ?></td>
          <td><?= $user['email'] ?></td>
          <td><?= $user['role'] ?></td>
          <td>
            <a href="delete_user.php?id=<?= $user['id'] ?>" class="btn btn-danger">Delete</a>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

  <!-- Appointments List -->
  <h2>Appointments</h2>
  <table class="table">
    <thead>
      <tr>
        <th>Appointment ID</th>
        <th>Patient</th>
        <th>Doctor</th>
        <th>Date</th>
        <th>Status</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($appointment = $appointments->fetch_assoc()): ?>
        <tr>
          <td><?= $appointment['id'] ?></td>
          <td><?= $appointment['patient_name'] ?></td>
          <td><?= $appointment['doctor_name'] ?></td>
          <td><?= $appointment['date'] ?></td>
          <td><?= $appointment['status'] ?></td>
          <td>
            <a href="approve_appointment.php?id=<?= $appointment['id'] ?>&status=approved" class="btn btn-success">Approve</a>
            <a href="approve_appointment.php?id=<?= $appointment['id'] ?>&status=declined" class="btn btn-danger">Decline</a>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

</body>
</html>
