<?php
include 'db_con/db_connect.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Trainer Booking - Indoor Hub</title>
  <link href="css/common.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
</head>
<body>

<div class="container-fluid">
  <div class="row">

    <!-- Sidebar -->
    <nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
      <div class="position-sticky pt-3">
        <div class="text-center mb-3">
          <img src="images/logo.jpg" alt="Indoor Hub Logo" width="80" class="mt-2">
          <h5 class="fw-bold">Registered User</h5>
        </div>
        <ul class="nav flex-column">
          <li class="nav-item"><a class="nav-link" href="Registereduserpage.php"><i class="bi bi-house-door-fill me-2"></i> Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="bookingslotpage.php"><i class="bi bi-calendar-check me-2"></i> Book Slots</a></li>
          <li class="nav-item"><a class="nav-link active" href="TrainerBookingpage.php"><i class="bi bi-person-plus me-2"></i> Book Trainer</a></li>
          <li class="nav-item"><a class="nav-link" href="paymentpage.php"><i class="bi bi-credit-card me-2"></i> Payments</a></li>
          <li class="nav-item"><a class="nav-link" href="notificationpage.php"><i class="bi bi-bell me-2"></i> Notifications</a></li>
          <li class="nav-item"><a class="nav-link" href="rup.php"><i class="bi bi-star me-2"></i> Reviews</a></li>
        </ul>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
      <h2 class="fw-bold mb-4">Available Trainers</h2>

      <!-- Trainers List -->
      <div class="row g-4">
        <div class="col-md-4">
          <div class="card h-100 shadow-sm text-center">
            <img src="images/nif.jpeg" class="card-img-top" alt="Trainer 1">
            <div class="card-body">
              <h5 class="card-title">John Doe</h5>
              <p class="card-text">Specialty: Badminton | Rating: 4.8</p>
              <button class="btn btn-primary btn-sm">Book Now</button>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card h-100 shadow-sm text-center">
            <img src="images/dinu.jpeg" class="card-img-top" alt="Trainer 2">
            <div class="card-body">
              <h5 class="card-title">Jane Smith</h5>
              <p class="card-text">Specialty: Basketball | Rating: 4.6</p>
              <button class="btn btn-primary btn-sm">Book Now</button>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card h-100 shadow-sm text-center">
            <img src="images/saci.jpeg" class="card-img-top" alt="Trainer 3">
            <div class="card-body">
              <h5 class="card-title">Michael Lee</h5>
              <p class="card-text">Specialty: Table Tennis | Rating: 4.9</p>
              <button class="btn btn-primary btn-sm">Book Now</button>
            </div>
          </div>
        </div>
      </div>

    </main>

  </div>
</div>

<script src="js/common.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
