<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Centre Manager - Bookings</title>
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
          <li class="nav-item"><a class="nav-link active" href="cm_bookings.php"><i class="bi bi-calendar-check me-2"></i> Bookings</a></li>
          <li class="nav-item"><a class="nav-link" href="cm_managetrainers.php"><i class="bi bi-person-lines-fill me-2"></i> Manage Trainers</a></li>
          <li class="nav-item"><a class="nav-link" href="cm_equipmentissues.php"><i class="bi bi-tools me-2"></i> Equipment Issues</a></li>
          <li class="nav-item"><a class="nav-link" href="cm_announcements.php"><i class="bi bi-megaphone me-2"></i> Announcements</a></li>
          <li class="nav-item"><a class="nav-link" href="cm_reports.php"><i class="bi bi-bar-chart-line me-2"></i> Reports</a></li>
        </ul>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
      <h2 class="fw-bold mb-4">Manage Bookings</h2>

      <!-- Bookings Table -->
      <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th>#</th>
              <th>User</th>
              <th>Facility</th>
              <th>Date</th>
              <th>Time Slot</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th>1</th>
              <td>nimal@example.com</td>
              <td>Badminton Court 1</td>
              <td>2025-06-30</td>
              <td>10:00 - 11:00 AM</td>
              <td><span class="badge bg-warning">Pending</span></td>
              <td>
                <button class="btn btn-sm btn-success"><i class="bi bi-check-circle"></i> Approve</button>
                <button class="btn btn-sm btn-danger"><i class="bi bi-x-circle"></i> Reject</button>
              </td>
            </tr>
            <tr>
              <th>2</th>
              <td>kasun@example.com</td>
              <td>Basketball Court</td>
              <td>2025-06-30</td>
              <td>3:00 - 5:00 PM</td>
              <td><span class="badge bg-success">Confirmed</span></td>
              <td>
                <button class="btn btn-sm btn-secondary"><i class="bi bi-pencil"></i> Reschedule</button>
                <button class="btn btn-sm btn-danger"><i class="bi bi-x-circle"></i> Cancel</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </main>
  </div>
</div>

<script src="js/common.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
