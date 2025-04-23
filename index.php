<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Fountain Spring Clinic</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <link href="style.css" rel="stylesheet" />
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
        <li class="nav-item"><a class="nav-link" href="#home">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="#appointments">Appointments</a></li>
        <li class="nav-item"><a class="nav-link" href="#doctors">Doctors</a></li>
        <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- Hero -->
<div id="home" class="hero">
  <div class="text-center">
    <h1 class="display-4 fw-bold">Caring For Your Health</h1>
    <p class="lead">Book appointments, view history, and connect with your doctor.</p>
    <a href="auth/dashboard.php" class="btn btn-primary btn-lg mt-3">Get Started</a>
  </div>
</div>

<!-- Services -->
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

<!-- Testimonials -->
<section class="container my-5">
  <h2 class="text-center mb-4 text-primary">What Our Patients Say</h2>
  <div class="row">
    <div class="col-md-6 fade-in-up">
      <blockquote class="blockquote">
        <p>"The doctors are friendly and professional. Booking is easy and fast!"</p>
        <footer class="blockquote-footer">Emily R.</footer>
      </blockquote>
    </div>
    <div class="col-md-6 fade-in-up">
      <blockquote class="blockquote">
        <p>"Highly recommend this clinic. They've helped me a lot over the years!"</p>
        <footer class="blockquote-footer">Daniel M.</footer>
      </blockquote>
    </div>
  </div>
</section>

<!-- FAQ -->
<section class="container my-5">
  <h2 class="text-center mb-4 text-primary">Frequently Asked Questions</h2>
  <div class="accordion fade-in-up" id="faqAccordion">
    <div class="accordion-item">
      <h2 class="accordion-header">
        <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#faq1">
          How do I book an appointment?
        </button>
      </h2>
      <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
        <div class="accordion-body">Log in to your dashboard and select a doctor and time slot.</div>
      </div>
    </div>
    <div class="accordion-item">
      <h2 class="accordion-header">
        <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#faq2">
          Can I view my previous medical history?
        </button>
      </h2>
      <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
        <div class="accordion-body">Yes! All your visit history is saved in your profile under Medical Records.</div>
      </div>
    </div>
  </div>
</section>

<!-- Contact -->
<section id="contact" class="container my-5">
  <h2 class="text-center mb-4 text-primary">Contact Us</h2>
  <p>If you have any questions or need assistance, feel free to reach out!</p>

  <div class="row">
    <div class="col-md-6">
      <form action="handle_contact.php" method="POST">
        <div class="mb-3">
          <label for="name" class="form-label">Full Name</label>
          <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
          <label for="email" class="form-label">Email address</label>
          <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
          <label for="message" class="form-label">Message</label>
          <textarea class="form-control" id="message" name="message" rows="3" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Send Message</button>
      </form>
    </div>

    <div class="col-md-6 clinic-info">
      <h5 class="text-primary">Clinic Info</h5>
      <p><strong>Phone:</strong> +1 (555) 123-4567</p>
      <p><strong>Email:</strong> support@fountainspringclinic.com</p>
      <p><strong>Address:</strong> 123 Wellness Street, Calgary, AB T2P 3G4, Canada</p>

      <h6 class="text-primary mt-4">Follow Us</h6>
      <div class="social-icons mt-2">
        <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
        <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
        <a href="#" class="twitter"><i class="bi bi-twitter-x"></i></a>
        <a href="#" class="tiktok"><i class="bi bi-tiktok"></i></a>
      </div>
    </div>
  </div>
</section>

<!-- Footer -->
<footer>
  &copy; <span id="year"></span> Fountain Spring Clinic. All rights reserved.
</footer>

<script>
  document.getElementById("year").textContent = new Date().getFullYear();
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
