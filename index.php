<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Indoor Hub - Home</title>
  <!-- <link rel="stylesheet" href="css/style.css" /> -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
</head>
<body>

<!-- Navigation -->
<nav class="navbar sticky-top navbar-expand-lg navbar-dark bg-primary shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold d-flex align-items-center" href="index.php">
      <img src="images/logo.jpg" alt="Indoor Hub Logo" width="40" height="40" class="me-2"> INDOOR HUB
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link active" href="#"><b>Home</b></a></li>
        <li class="nav-item"><a class="nav-link" href="facilities.php"><b>Facilities</b></a></li>
        <li class="nav-item"><a class="nav-link" href="about.php"><b>About Us</b></a></li>
        <li class="nav-item"><a class="nav-link" href="contact.php"><b>Review</b></a></li>
        <li class="nav-item"><a class="btn btn-light me-2" href="login.php">Login</a></li>
        <li class="nav-item"><a class="btn btn-light border" href="register.php">Register</a></li>
      </ul>
    </div>
  </div>
</nav>


<!--add new hero-->
<section class="position-relative">
    <?php include('frontend/imageMove.php'); ?>
</section>

<!-- Include the rest of the sections here -->
<?php include('frontend/home_sections.php'); ?>

<!-- Footer -->
<footer class="bg-dark text-center text-lg-start mt-5 text-white">
  <div class="container p-4">
    <div class="row">
      <div class="col-lg-4 col-md-6 mb-4 mb-md-0">
        <img src="images/logo.jpg" alt="Indoor Hub Logo" width="120" class="mb-2">
        <h5 class="text-uppercase fw-bold">Indoor Hub</h5>
        <p>Effortlessly book and manage indoor sports facilities and events with our user-friendly platform.</p>
      </div>
      <div class="col-lg-4 col-md-6 mb-4 mb-md-0">
        <h5 class="text-uppercase fw-bold">Quick Links</h5>
        <ul class="list-unstyled mb-0">
          <li><a href="index.php" class="text-white">Home</a></li>
          <li><a href="facilities.php" class="text-white">Facilities</a></li>
          <li><a href="about.php" class="text-white">About Us</a></li>
          <li><a href="contact.php" class="text-white">Contact</a></li>
        </ul>
      </div>
      <div class="col-lg-4 col-md-12 mb-4 mb-md-0">
        <h5 class="text-uppercase fw-bold">Contact</h5>
        <ul class="list-unstyled">
          <li><i class="bi bi-geo-alt-fill"></i> Colombo, Sri Lanka</li>
          <li><i class="bi bi-envelope-fill"></i> support@indoorhub.com</li>
          <li><i class="bi bi-telephone-fill"></i> +94 77 123 4567</li>
        </ul>
      </div>
    </div>
  </div>
  <div class="text-center p-3 bg-primary">
    © 2025 Indoor Hub. All rights reserved.
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/script.js"></script>
</body>
</html>
