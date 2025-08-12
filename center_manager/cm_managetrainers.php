<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Centre Manager - Manage Trainers</title>
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
          <li class="nav-item"><a class="nav-link"  href="cm_managetrainers.html"><i class="bi bi-person-lines-fill me-2"></i> Manage Trainers</a></li>
          <li class="nav-item"><a class="nav-link" href="cm_equipmentissues.html"><i class="bi bi-tools me-2"></i> Equipment Issues</a></li>
          <li class="nav-item"><a class="nav-link" href="cm_announcements.html"><i class="bi bi-megaphone me-2"></i> Announcements</a></li>
          <li class="nav-item"><a class="nav-link" href="cm_reports.html"><i class="bi bi-bar-chart-line me-2"></i> Reports</a></li>
        </ul>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
      <h2 class="fw-bold mb-4">Manage Trainers</h2>

      <!-- Add Trainer Form -->
      <div class="card shadow-sm mb-4">
        <div class="card-body">
          <h5 class="card-title fw-bold">Add New Trainer</h5>
          <form>
            <div class="row g-3">
              <div class="col-md-4">
                <input type="text" class="form-control" placeholder="Full Name" required>
              </div>
              <div class="col-md-4">
                <input type="email" class="form-control" placeholder="Email" required>
              </div>
              <div class="col-md-4">
                <input type="text" class="form-control" placeholder="Specialization" required>
              </div>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Add Trainer</button>
          </form>
        </div>
      </div>

      <!-- Trainers List -->
      <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>Email</th>
              <th>Specialization</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th>1</th>
              <td>Sahan Perera</td>
              <td>sahan@example.com</td>
              <td>Badminton Coach</td>
              <td>
                <button class="btn btn-sm btn-secondary"><i class="bi bi-pencil"></i> Edit</button>
                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Remove</button>
              </td>
            </tr>
            <tr>
              <th>2</th>
              <td>Kasuni Silva</td>
              <td>kasuni@example.com</td>
              <td>Basketball Coach</td>
              <td>
                <button class="btn btn-sm btn-secondary"><i class="bi bi-pencil"></i> Edit</button>
                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Remove</button>
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

