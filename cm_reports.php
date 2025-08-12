<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Centre Manager - Reports</title>
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
          <h5 class="fw-bold">Centre Manager</h5>
        </div>
        <ul class="nav flex-column">
          <li class="nav-item"><a class="nav-link" href="centremanagerdashboard.php"><i class="bi bi-house-door-fill me-2"></i> Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="cm_bookings.php"><i class="bi bi-calendar-check me-2"></i> Bookings</a></li>
          <li class="nav-item"><a class="nav-link " href="cm_managetrainers.php"><i class="bi bi-person-lines-fill me-2"></i> Manage Trainers</a></li>
          <li class="nav-item"><a class="nav-link" href="cm_equipmentissues.php"><i class="bi bi-tools me-2"></i> Equipment Issues</a></li>
          <li class="nav-item"><a class="nav-link" href="cm_announcements.php"><i class="bi bi-megaphone me-2"></i> Announcements</a></li>
          <li class="nav-item"><a class="nav-link " href="cm_reports.php"><i class="bi bi-bar-chart-line me-2"></i> Reports</a></li>
        </ul>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
      <h2 class="fw-bold mb-4">Reports</h2>

      <!-- Booking Summary Report -->
      <div class="card shadow-sm mb-4">
        <div class="card-body">
          <h5 class="card-title fw-bold">Booking Summary</h5>
          <table class="table table-striped table-hover align-middle">
            <thead class="table-light">
              <tr>
                <th>Date</th>
                <th>Total Bookings</th>
                <th>Total Revenue (LKR)</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>2025-06-28</td>
                <td>12</td>
                <td>36,000</td>
              </tr>
              <tr>
                <td>2025-06-27</td>
                <td>9</td>
                <td>27,000</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Trainer Performance Report -->
      <div class="card shadow-sm mb-4">
        <div class="card-body">
          <h5 class="card-title fw-bold">Trainer Performance</h5>
          <table class="table table-striped table-hover align-middle">
            <thead class="table-light">
              <tr>
                <th>Trainer Name</th>
                <th>Sessions Conducted</th>
                <th>Average Rating</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Sahan Perera</td>
                <td>5</td>
                <td>4.8</td>
              </tr>
              <tr>
                <td>Kasuni Silva</td>
                <td>4</td>
                <td>4.5</td>
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
