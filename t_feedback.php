<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Trainer Feedback</title>
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
          <li class="nav-item"><a class="nav-link" href="Trainerdashboard.html"><i class="bi bi-house-door-fill me-2"></i> Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="trainer_profile.html"><i class="bi bi-person-lines-fill me-2"></i> My Profile</a></li>
          <li class="nav-item"><a class="nav-link" href="t_availability.html"><i class="bi bi-calendar-check me-2"></i> Availability</a></li>
          <li class="nav-item"><a class="nav-link" href="t_requests.html"><i class="bi bi-check2-square me-2"></i> Requests</a></li>
          <li class="nav-item"><a class="nav-link active" href="t_feedback.html"><i class="bi bi-chat-left-text me-2"></i> Feedback</a></li>
        </ul>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
      <h2 class="fw-bold mb-4">Feedback</h2>

      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="card-title">User Feedback & Ratings</h5>
          <table class="table table-striped table-hover align-middle">
            <thead class="table-light">
              <tr>
                <th>User</th>
                <th>Rating</th>
                <th>Feedback</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Nimal Silva</td>
                <td>⭐⭐⭐⭐</td>
                <td>Excellent coaching. Improved my technique significantly!</td>
                <td>2025-06-20</td>
              </tr>
              <tr>
                <td>Amaya Perera</td>
                <td>⭐⭐⭐⭐⭐</td>
                <td>Very professional and motivating trainer. Highly recommended.</td>
                <td>2025-06-18</td>
              </tr>
              <tr>
                <td>Ruwan Fernando</td>
                <td>⭐⭐⭐</td>
                <td>Good session but started a bit late.</td>
                <td>2025-06-15</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

    </main>
  </div>
</div>

<script src="js/common.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
