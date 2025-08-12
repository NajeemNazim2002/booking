<?php
include 'db_con/db_connect.php';
require_once 'Auth.php'; // adjust path if needed

Auth::requireRole('manager');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Centre Manager Dashboard - Indoor Hub</title>
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
          <li class="nav-item"><a class="nav-link active" href="centremanagerdashboard.php"><i class="bi bi-house-door-fill me-2"></i> Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="cm_bookings.php"><i class="bi bi-calendar-check me-2"></i> Bookings</a></li>
          <li class="nav-item"><a class="nav-link" href="cm_managetrainers.php"><i class="bi bi-person-lines-fill me-2"></i> Manage Trainers</a></li>
          <li class="nav-item"><a class="nav-link" href="cm_equipmentissues.php"><i class="bi bi-tools me-2"></i> Equipment Issues</a></li>
          <li class="nav-item"><a class="nav-link" href="cm_announcements.php"><i class="bi bi-megaphone me-2"></i> Announcements</a></li>
          <li class="nav-item"><a class="nav-link" href="cm_reports.php"><i class="bi bi-bar-chart-line me-2"></i> Reports</a></li>
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
      <h2 class="fw-bold mb-4">Centre Manager Dashboard</h2>

      <!-- System Overview -->
      <div class="row g-4 mb-4">
        <div class="col-md-3">
          <div class="card h-100 shadow-sm text-center">
            <div class="card-body">
              <i class="bi bi-calendar-check fs-1 text-primary mb-2"></i>
              <h5 class="card-title">35 Bookings</h5>
              <p class="card-text">Today’s bookings at your centre.</p>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="card h-100 shadow-sm text-center">
            <div class="card-body">
              <i class="bi bi-people fs-1 text-primary mb-2"></i>
              <h5 class="card-title">12 Trainers</h5>
              <p class="card-text">Available trainers/coaches/referees.</p>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="card h-100 shadow-sm text-center">
            <div class="card-body">
              <i class="bi bi-exclamation-triangle fs-1 text-primary mb-2"></i>
              <h5 class="card-title">2 Issues</h5>
              <p class="card-text">Pending equipment issues.</p>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="card h-100 shadow-sm text-center">
            <div class="card-body">
              <i class="bi bi-megaphone fs-1 text-primary mb-2"></i>
              <h5 class="card-title">3 Notices</h5>
              <p class="card-text">Active announcements.</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Quick Actions -->
      <div class="row g-4">
        <div class="col-md-4">
          <div class="card h-100 shadow-sm text-center">
            <div class="card-body">
              <i class="bi bi-calendar-check fs-1 text-primary mb-3"></i>
              <h5 class="card-title">Approve/Reject Bookings</h5>
              <p class="card-text">Manage booking requests efficiently.</p>
              <a href="cm_bookings.php" class="btn btn-primary">Manage Bookings</a>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card h-100 shadow-sm text-center">
            <div class="card-body">
              <i class="bi bi-person-plus fs-1 text-primary mb-3"></i>
              <h5 class="card-title">Add/Remove Trainers</h5>
              <p class="card-text">Manage trainer and coach profiles.</p>
              <a href="cm_managetrainers.php" class="btn btn-primary">Manage Trainers</a>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card h-100 shadow-sm text-center">
            <div class="card-body">
              <i class="bi bi-megaphone fs-1 text-primary mb-3"></i>
              <h5 class="card-title">Post Announcements</h5>
              <p class="card-text">Notify users about updates.</p>
              <a href="cm_announcements.php" class="btn btn-primary">Announcements</a>
            </div>
          </div>
        </div>
      </div>

      <!-- Extra Features -->
      <div class="row g-4 mt-2">
        <!-- Upload Photos -->
        <div class="col-md-6">
          <div class="card h-100 shadow-sm text-center">
            <div class="card-body">
              <i class="bi bi-cloud-upload fs-1 text-primary mb-3"></i>
              <h5 class="card-title">Upload Facility Photos</h5>
              <p class="card-text">Add or update your centre’s facility photos.</p>
              <a href="uploadphotos.php" class="btn btn-primary">Upload Photos</a>
            </div>
          </div>
        </div>

        <!-- Block Maintenance Dates -->
        <div class="col-md-6">
          <div class="card h-100 shadow-sm text-center">
            <div class="card-body">
              <i class="bi bi-calendar-x fs-1 text-primary mb-3"></i>
              <h5 class="card-title">Block Maintenance Dates</h5>
              <p class="card-text">Mark unavailable dates for repairs or closures.</p>
              <a href="blockmaintenancedate.php" class="btn btn-primary">Block Dates</a>
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
