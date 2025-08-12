<?php
global $conn;
session_start();
include 'db_con/db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Notifications - Indoor Hub</title>
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
          <li class="nav-item"><a class="nav-link" href="trainerbookingpage.php"><i class="bi bi-person-plus me-2"></i> Book Trainer</a></li>
          <li class="nav-item"><a class="nav-link" href="paymentpage.php"><i class="bi bi-credit-card me-2"></i> Payments</a></li>
          <li class="nav-item"><a class="nav-link active" href="notificationpage.php"><i class="bi bi-bell me-2"></i> Notifications</a></li>
          <li class="nav-item"><a class="nav-link" href="rup.php"><i class="bi bi-star me-2"></i> Reviews</a></li>
        </ul>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
      <h2 class="fw-bold mb-4">Notifications</h2>

      <!-- Notifications List -->
      <div class="list-group">
        <a href="#" class="list-group-item list-group-item-action">
          <i class="bi bi-bell-fill me-2"></i> Your booking for Badminton Court 1 on 2025-07-02 has been confirmed.
          <span class="text-muted small float-end">2 hours ago</span>
        </a>
        <a href="#" class="list-group-item list-group-item-action">
          <i class="bi bi-bell-fill me-2"></i> Payment of Rs. 2,500 received successfully.
          <span class="text-muted small float-end">1 day ago</span>
        </a>
        <a href="#" class="list-group-item list-group-item-action">
          <i class="bi bi-bell-fill me-2"></i> Trainer John Doe has accepted your training request.
          <span class="text-muted small float-end">3 days ago</span>
        </a>
        <a href="#" class="list-group-item list-group-item-action">
          <i class="bi bi-bell-fill me-2"></i> New slots available for Table Tennis on 2025-07-05.
          <span class="text-muted small float-end">5 days ago</span>
        </a>
      </div>

    </main>

  </div>
</div>

<script src="js/common.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
