<?php
include 'db_con/db_connect.php';
require_once 'Auth.php'; // adjust path if needed

Auth::requireRole('trainer');
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Trainer Dashboard - Indoor Hub</title>
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
          <h5 class="fw-bold">Trainer</h5>
        </div>
        <ul class="nav flex-column">
          <li class="nav-item"><a class="nav-link active" href="trainer_dashboard.php"><i class="bi bi-house-door-fill me-2"></i> Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="trainer_profile.php"><i class="bi bi-person-lines-fill me-2"></i> My Profile</a></li>
          <li class="nav-item"><a class="nav-link" href="trainer_availability.php"><i class="bi bi-calendar-check me-2"></i> Availability</a></li>
          <li class="nav-item"><a class="nav-link" href="trainer_requests.php"><i class="bi bi-check2-square me-2"></i> Requests</a></li>
          <li class="nav-item"><a class="nav-link" href="trainer_feedback.php"><i class="bi bi-chat-left-text me-2"></i> Feedback</a></li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">
                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                </a>
            </li>
        </ul>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
      <h2 class="fw-bold mb-4">Trainer Dashboard</h2>

      <!-- System Overview -->
      <div class="row g-4 mb-4">
        <div class="col-md-4">
          <div class="card h-100 shadow-sm text-center">
            <div class="card-body">
              <i class="bi bi-calendar-check fs-1 text-primary mb-2"></i>
              <h5 class="card-title">8 Bookings</h5>
              <p class="card-text">Upcoming training sessions.</p>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card h-100 shadow-sm text-center">
            <div class="card-body">
              <i class="bi bi-clock fs-1 text-primary mb-2"></i>
              <h5 class="card-title">Today: 3 Sessions</h5>
              <p class="card-text">Scheduled for today.</p>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card h-100 shadow-sm text-center">
            <div class="card-body">
              <i class="bi bi-star fs-1 text-primary mb-2"></i>
              <h5 class="card-title">Rating: 4.7</h5>
              <p class="card-text">Based on user feedback.</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Quick Actions -->
      <div class="row g-4">
        <div class="col-md-4">
          <div class="card h-100 shadow-sm text-center">
            <div class="card-body">
              <i class="bi bi-person-lines-fill fs-1 text-primary mb-3"></i>
              <h5 class="card-title">Update Profile</h5>
              <p class="card-text">Edit your profile details.</p>
              <a href="trainer_profile.php" class="btn btn-primary">Edit Profile</a>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card h-100 shadow-sm text-center">
            <div class="card-body">
              <i class="bi bi-calendar-check fs-1 text-primary mb-3"></i>
              <h5 class="card-title">Manage Availability</h5>
              <p class="card-text">Set your available time slots.</p>
              <a href="trainer_availability.php" class="btn btn-primary">Availability</a>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card h-100 shadow-sm text-center">
            <div class="card-body">
              <i class="bi bi-check2-square fs-1 text-primary mb-3"></i>
              <h5 class="card-title">Training Requests</h5>
              <p class="card-text">Accept or reject requests.</p>
              <a href="trainer_requests.php" class="btn btn-primary">View Requests</a>
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
