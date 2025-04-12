<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Welcome | Fountain Spring Clinic</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #f0f4f8;
      font-family: 'Segoe UI', sans-serif;
    }

    .auth-box {
      background: white;
      border-radius: 1rem;
      padding: 2rem;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
      text-align: center;
    }

    .btn-lg {
      padding: 0.75rem 2rem;
      font-size: 1.2rem;
    }

    footer {
      text-align: center;
      margin-top: 4rem;
      color: #777;
    }
  </style>
</head>
<body>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
  <div class="col-md-6 auth-box">
    <h2 class="mb-4 text-primary">Welcome to Fountain Spring Clinic</h2>
    <p class="mb-4">Please choose an option to get started:</p>
    <div class="d-grid gap-3">
      <a href="register.php" class="btn btn-success btn-lg">Register as a New Patient</a>
      <a href="login.php" class="btn btn-primary btn-lg">Login to Existing Account</a>
      <a href="../index.php" class="btn btn-outline-secondary btn-lg">← Back to Home</a>
    </div>
  </div>
</div>

<footer>
  &copy; <?= date('Y') ?> Fountain Spring Clinic. All rights reserved.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
