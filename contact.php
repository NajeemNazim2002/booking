<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Indoor Hub - Contact</title>
  <link href="css/style.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar sticky-top navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container">
      <a class="navbar-brand fw-bold d-flex align-items-center" href="index.php">
        <img src="images/logo.jpg" alt="Indoor Hub Logo" width="40" height="40" class="me-2">
        INDOOR HUB
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
          <li class="nav-item"><a class="nav-link" href="index.php"><b>Home</b></a></li>
          <li class="nav-item"><a class="nav-link" href="facilities.php"><b>Facilities</b></a></li>
          <li class="nav-item"><a class="nav-link" href="about.php"><b>About us</b></a></li>
          <li class="nav-item"><a class="nav-link active" href="#"><b>Contact</b></a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Contact Content -->
  <section class="py-5">
    <div class="container">
      <h2 class="fw-bold text-center mb-4">Contact Us</h2>
      <form class="mx-auto" style="max-width: 600px;">
        <div class="mb-3">
          <label for="name" class="form-label">Full Name</label>
          <input type="text" class="form-control" id="name" placeholder="Your Name">
        </div>
        <div class="mb-3">
          <label for="email" class="form-label">Email Address</label>
          <input type="email" class="form-control" id="email" placeholder="name@example.com">
        </div>
        <div class="mb-3">
          <label for="message" class="form-label">Message</label>
          <textarea class="form-control" id="message" rows="4" placeholder="Your message here..."></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Send Message</button>
      </form>
    </div>
  </section>

   <!-- Footer -->
<footer class="bg-dark text-center text-lg-start mt-5">
  <div class="container p-4">
    <div class="row">

      <!-- Logo and About -->
      <div class="col-lg-4 col-md-6 mb-4 mb-md-0 text-white">
        <img src="images/logo.jpg" alt="Indoor Hub Logo" width="120" class="mb-2">
        <h5 class="text-uppercase fw-bold">Indoor Hub</h5>
        <p>
          Effortlessly book and manage indoor sports facilities and events with our user-friendly platform.
        </p>
      </div>

      <!-- Quick Links -->
      <div class="col-lg-4 col-md-6 mb-4 mb-md-0">
        <h5 class="text-uppercase fw-bold text-white">Quick Links</h5>
        <ul class="list-unstyled mb-0">
          <li><a href="index.html" class="text-white">Home</a></li>
          <li><a href="facilities.html" class="text-white">Facilities</a></li>
          <li><a href="about.html" class="text-white">About Us</a></li>
          <li><a href="contact.html" class="text-white">Contact</a></li>
        </ul>
      </div>

      <!-- Contact Info -->
      <div class="col-lg-4 col-md-12 mb-4 mb-md-0 text-white">
        <h5 class="text-uppercase fw-bold">Contact</h5>
        <ul class="list-unstyled">
          <li><i class="bi bi-geo-alt-fill"></i> Colombo, Sri Lanka</li>
          <li><i class="bi bi-envelope-fill"></i> support@indoorhub.com</li>
          <li><i class="bi bi-telephone-fill"></i> +94 77 123 4567</li>
        </ul>
      </div>

    </div>
  </div>


  <!-- Footer -->
  <footer class="bg-primary text-white py-4 mt-4">
    <div class="container text-center">
      <p class="mb-0">&copy; 2025 Indoor Hub. All rights reserved.</p>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="js/script.js"></script>
</body>
</html>
