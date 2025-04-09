<?php
include '../config/db.php';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $name = trim($_POST['name']);
  $email = trim($_POST['email']);
  $password = $_POST['password'];
  $role = $_POST['role'];

  if (!$name || !$email || !$password || !$role) {
    $errors[] = "All fields are required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email address.";
  } else {
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
      $errors[] = "Email already exists.";
    } else {
      $hash = password_hash($password, PASSWORD_DEFAULT);
      $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
      $stmt->bind_param("ssss", $name, $email, $hash, $role);
      $stmt->execute();
      header("Location: login.php?registered=1");
      exit;
    }
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Register</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
  <h2>Register</h2>
  <?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
      <?php foreach ($errors as $error): ?>
        <p><?= $error ?></p>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
  <form method="POST" class="form-group">
    <input type="text" name="name" placeholder="Name" class="form-control mb-2" required>
    <input type="email" name="email" placeholder="Email" class="form-control mb-2" required>
    <input type="password" name="password" placeholder="Password" class="form-control mb-2" required>
    <select name="role" class="form-control mb-2">
      <option value="patient">Patient</option>
      <option value="doctor">Doctor</option>
      <option value="admin">Admin</option>
    </select>
    <button type="submit" class="btn btn-primary">Register</button>
  </form>
  <a href="../index.php">← Back</a>
</body>
</html>
