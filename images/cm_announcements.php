<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Centre Manager - Announcements</title>
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
          <li class="nav-item"><a class="nav-link" href="centremanagerdashboard.html"><i class="bi bi-house-door-fill me-2"></i> Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="cm_bookings.html"><i class="bi bi-calendar-check me-2"></i> Bookings</a></li>
          <li class="nav-item"><a class="nav-link " href="cm_managetrainers.html"><i class="bi bi-person-lines-fill me-2"></i> Manage Trainers</a></li>
          <li class="nav-item"><a class="nav-link" href="cm_equipmentissues.html"><i class="bi bi-tools me-2"></i> Equipment Issues</a></li>
          <li class="nav-item"><a class="nav-link active" href="cm_announcements.html"><i class="bi bi-megaphone me-2"></i> Announcements</a></li>
          <li class="nav-item"><a class="nav-link" href="cm_reports.html"><i class="bi bi-bar-chart-line me-2"></i> Reports</a></li>
        </ul>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
      <h2 class="fw-bold mb-4">Announcements</h2>

      <!-- Add Announcement Form -->
      <div class="card shadow-sm mb-4">
        <div class="card-body">
          <h5 class="card-title fw-bold">Add New Announcement</h5>
          <form>
            <div class="mb-3">
              <input type="text" class="form-control" placeholder="Announcement Title" required>
            </div>
            <div class="mb-3">
              <textarea class="form-control" rows="3" placeholder="Announcement Details" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Publish</button>
          </form>
        </div>
      </div>

      <!-- Announcements List -->
      <div class="list-group">
        <a href="#" class="list-group-item list-group-item-action">
          <div class="d-flex w-100 justify-content-between">
            <h5 class="mb-1">Maintenance Notice</h5>
            <small>June 28, 2025</small>
          </div>
          <p class="mb-1">The badminton courts will be closed for maintenance from 3 PM to 5 PM.</p>
        </a>
        <a href="#" class="list-group-item list-group-item-action">
          <div class="d-flex w-100 justify-content-between">
            <h5 class="mb-1">New Trainer Available</h5>
            <small>June 27, 2025</small>
          </div>
          <p class="mb-1">We have appointed a new basketball coach for evening sessions.</p>
        </a>
      </div>
    </main>
  </div>
</div>

<script src="js/common.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
