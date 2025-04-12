<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Welcome to Your Clinic</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f8f9fa;
    }

    .hero {
      background: url('../assets/clinic-banner.jpg') center/cover no-repeat;
      height: 400px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      text-shadow: 1px 1px 4px #000;
    }

    .services .card {
      border: none;
      border-radius: 1rem;
      transition: 0.3s ease;
    }

    .services .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }

    .navbar-brand {
      font-weight: bold;
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
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
  <div class="container">
    <a class="navbar-brand text-primary" href="#">Fountain Spring Clinic</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto">
        <!-- Updated links -->
        <li class="nav-item"><a class="nav-link" href="#home">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="#appointments">Appointments</a></li>
        <li class="nav-item"><a class="nav-link" href="#doctors">Doctors</a></li>
        <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- Hero Banner -->
<div id="home" class="hero">
  <div class="text-center">
    <h1 class="display-4 fw-bold">Caring For Your Health</h1>
    <p class="lead">Book appointments, view history, and connect with your doctor.</p>
    <a href="auth/dashboard.php" class="btn btn-primary btn-lg mt-3">Get Started</a>
  </div>
</div>

<!-- Services Section -->
<section id="appointments" class="services container my-5">
  <h2 class="text-center mb-4 text-primary">Our Services</h2>
  <div class="row">
    <div class="col-md-4">
      <div class="card p-4">
        <h4>Appointment Booking</h4>
        <p>Schedule visits with our certified doctors easily through your dashboard.</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card p-4">
        <h4>Medical Records</h4>
        <p>Access and manage your medical history in one convenient place.</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card p-4">
        <h4>Patient Support</h4>
        <p>Reach out to our team or chat with your doctor directly through the portal.</p>
      </div>
    </div>
  </div>
</section>


<!-- Contact Section (Placeholder) -->
<section id="contact" class="container my-5">
  <h2 class="text-center mb-4 text-primary">Contact Us</h2>
  <p>If you have any questions or need assistance, feel free to reach out!</p>
  <form>
    <div class="mb-3">
      <label for="name" class="form-label">Full Name</label>
      <input type="text" class="form-control" id="name" placeholder="Your Name">
    </div>
    <div class="mb-3">
      <label for="email" class="form-label">Email address</label>
      <input type="email" class="form-control" id="email" placeholder="Your Email">
    </div>
    <div class="mb-3">
      <label for="message" class="form-label">Message</label>
      <textarea class="form-control" id="message" rows="3" placeholder="Your Message"></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Send Message</button>
  </form>
</section>

<!-- Footer -->
<footer>
  &copy; <?= date("Y") ?> Fountain Spring Clinic. All rights reserved.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
