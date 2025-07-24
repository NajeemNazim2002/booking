<?php
session_start();
include 'db_connect.php';

// Redirect if not admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch dashboard statistics
$centreCount = $conn->query("SELECT COUNT(*) AS count FROM centres")->fetch_assoc()['count'] ?? 0;
$userCount = $conn->query("SELECT COUNT(*) AS count FROM users")->fetch_assoc()['count'] ?? 0;
$bookingCount = $conn->query("SELECT COUNT(*) AS count FROM bookings")->fetch_assoc()['count'] ?? 0;
$paymentTotal = $conn->query("SELECT SUM(amount) AS total FROM payments")->fetch_assoc()['total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard - Indoor Hub</title>
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
          <h5 class="fw-bold">Administrator</h5>
        </div>
        <ul class="nav flex-column">
          <li class="nav-item"><a class="nav-link active" href="admin_dashboard.php"><i class="bi bi-house-door-fill me-2"></i> Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="managecentres.php"><i class="bi bi-building me-2"></i> Manage Centres</a></li>
          <li class="nav-item"><a class="nav-link" href="manageusers.php"><i class="bi bi-people me-2"></i> Manage Users</a></li>
          <li class="nav-item"><a class="nav-link" href="manageroles.php"><i class="bi bi-shield-lock me-2"></i> Manage Roles</a></li>
          <li class="nav-item"><a class="nav-link" href="systemsettings.php"><i class="bi bi-gear me-2"></i> System Settings</a></li>
          <li class="nav-item"><a class="nav-link" href="reports.php"><i class="bi bi-graph-up me-2"></i> Reports</a></li>
          <li class="nav-item"><a class="nav-link" href="complaints.php"><i class="bi bi-exclamation-circle me-2"></i> Complaints</a></li>
        </ul>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
      <h2 class="fw-bold mb-4">Administrator Dashboard</h2>

      <!-- System Overview -->
      <div class="row g-4 mb-4">
        <div class="col-md-3">
          <div class="card h-100 shadow-sm text-center">
            <div class="card-body">
              <i class="bi bi-building fs-1 text-primary mb-2"></i>
              <h5 class="card-title"><?= $centreCount ?> Centres</h5>
              <p class="card-text">Total managed indoor centres.</p>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="card h-100 shadow-sm text-center">
            <div class="card-body">
              <i class="bi bi-people fs-1 text-primary mb-2"></i>
              <h5 class="card-title"><?= $userCount ?> Users</h5>
              <p class="card-text">Registered platform users.</p>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="card h-100 shadow-sm text-center">
            <div class="card-body">
              <i class="bi bi-bookmark-check fs-1 text-primary mb-2"></i>
              <h5 class="card-title"><?= $bookingCount ?> Bookings</h5>
              <p class="card-text">Total active bookings.</p>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="card h-100 shadow-sm text-center">
            <div class="card-body">
              <i class="bi bi-cash-stack fs-1 text-primary mb-2"></i>
              <h5 class="card-title">Rs. <?= number_format($paymentTotal ?? 0, 2) ?></h5>
              <p class="card-text">Total payments received.</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Quick Actions -->
      <div class="row g-4">
        <div class="col-md-4">
          <div class="card h-100 shadow-sm text-center">
            <div class="card-body">
              <i class="bi bi-building fs-1 text-primary mb-3"></i>
              <h5 class="card-title">Manage Centres</h5>
              <p class="card-text">Add, edit, delete indoor centres.</p>
              <a href="managecentres.php" class="btn btn-primary">Go</a>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card h-100 shadow-sm text-center">
            <div class="card-body">
              <i class="bi bi-people fs-1 text-primary mb-3"></i>
              <h5 class="card-title">Manage Users</h5>
              <p class="card-text">View and edit all users.</p>
              <a href="manageusers.php" class="btn btn-primary">Go</a>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card h-100 shadow-sm text-center">
            <div class="card-body">
              <i class="bi bi-shield-lock fs-1 text-primary mb-3"></i>
              <h5 class="card-title">Manage Roles</h5>
              <p class="card-text">Assign roles and permissions.</p>
              <a href="manageroles.php" class="btn btn-primary">Go</a>
            </div>
          </div>
        </div>
      </div>

      <div class="row g-4 mt-2">
        <div class="col-md-4">
          <div class="card h-100 shadow-sm text-center">
            <div class="card-body">
              <i class="bi bi-graph-up fs-1 text-primary mb-3"></i>
              <h5 class="card-title">Reports</h5>
              <p class="card-text">Generate and view reports.</p>
              <a href="reports.php" class="btn btn-primary">Reports</a>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card h-100 shadow-sm text-center">
            <div class="card-body">
              <i class="bi bi-exclamation-circle fs-1 text-primary mb-3"></i>
              <h5 class="card-title">Complaints</h5>
              <p class="card-text">Handle user complaints.</p>
              <a href="complaints.php" class="btn btn-primary">View Complaints</a>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card h-100 shadow-sm text-center">
            <div class="card-body">
              <i class="bi bi-cloud-upload fs-1 text-primary mb-3"></i>
              <h5 class="card-title">Upload Facility Photos</h5>
              <p class="card-text">Upload and manage images for each indoor centre.</p>
              <a href="uploadphotos.php" class="btn btn-primary">Upload</a>
            </div>
          </div>
        </div>
      </div>

      <div class="row g-4 mt-2">
        <div class="col-md-6">
          <div class="card h-100 shadow-sm text-center">
            <div class="card-body">
              <i class="bi bi-calendar-x fs-1 text-primary mb-3"></i>
              <h5 class="card-title">Block Maintenance Dates</h5>
              <p class="card-text">Schedule unavailable dates for maintenance or closures.</p>
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