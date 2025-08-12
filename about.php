<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Indoor Hub - About Us</title>
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
          <li class="nav-item"><a class="nav-link active" href="#"><b>About Us</b></a></li>
          <li class="nav-item"><a class="nav-link" href="contact.php"><b>Contact</b></a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- About Hero Section -->
  <section class="py-5 bg-light">
    <div class="container text-center">
      <h1 class="fw-bold mb-3">About Indoor Hub</h1>
      <p class="lead">Your trusted partner in indoor facility booking and management.</p>
    </div>
  </section>

  <!-- Our Story Section -->
  <section class="py-5">
    <div class="container">
      <div class="row align-items-center g-5">
        <div class="col-md-6">
          <img src="images/storry.jpeg" class="img-fluid rounded shadow" alt="Our Story">
        </div>
        <div class="col-md-6">
          <h2 class="fw-bold mb-3">Our Story</h2>
          <p>Indoor Hub was founded to revolutionise the way people access and manage indoor facilities. We noticed the struggle faced by both facility owners and users in scheduling, availability tracking, and efficient space utilisation.</p>
          <p>With our easy-to-use platform, owners can list and manage their facilities seamlessly, while users can book with confidence and convenience.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Our Mission Section -->
  <section class="py-5 bg-light">
    <div class="container">
      <div class="row align-items-center g-5">
        <div class="col-md-6 order-md-2">
          <img src="images/mission.jpeg" class="img-fluid rounded shadow" alt="Our Mission">
        </div>
        <div class="col-md-6 order-md-1">
          <h2 class="fw-bold mb-3">Our Mission</h2>
          <p>Our mission is to simplify and enhance indoor facility management for everyone. We focus on transparency, accessibility, and maximising the value of every booking made through our platform.</p>
          <p>Whether you are a sports enthusiast, trainer, or event organiser, Indoor Hub ensures you spend less time managing and more time enjoying your activities.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Meet Our Team Section -->
  <section class="py-5">
    <div class="container text-center">
      <h2 class="fw-bold mb-5">Meet Our Team</h2>
      <div class="row g-4">
        <div class="col-md-4">
          <div class="card border-0 shadow-sm">
            <img src="images/nif.jpeg" class="card-img-top" alt="Team Member 1">
            <div class="card-body">
              <h5 class="card-title">Nifan Fernando</h5>
              <p class="card-text">Founder & CEO</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card border-0 shadow-sm">
            <img src="images/dinu.jpeg" class="card-img-top" alt="Team Member 2">
            <div class="card-body">
              <h5 class="card-title">Dinuka Perera</h5>
              <p class="card-text">Operations Manager</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card border-0 shadow-sm">
            <img src="images/saci.jpeg" class="card-img-top" alt="Team Member 3">
            <div class="card-body">
              <h5 class="card-title">Sachini Jayasooriya</h5>
              <p class="card-text">Marketing Head</p>
            </div>
          </div>
        </div>
      </div>
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
  <footer class="bg-primary text-white py-4 mt-auto">
    <div class="container text-center">
      <p class="mb-0">&copy; 2025 Indoor Hub. All rights reserved.</p>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="js/script.js"></script>
</body>
</html>
