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
  <title>Reviews & Ratings</title>
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
          <li class="nav-item"><a class="nav-link" href="Bookingslotpage.php"><i class="bi bi-calendar-check me-2"></i> Book Slots</a></li>
          <li class="nav-item"><a class="nav-link" href="TrainerBookingpage.php"><i class="bi bi-person-plus me-2"></i> Book Trainer</a></li>
          <li class="nav-item"><a class="nav-link" href="paymentpage.php"><i class="bi bi-credit-card me-2"></i> Payments</a></li>
          <li class="nav-item"><a class="nav-link" href="notificationpage.php"><i class="bi bi-bell me-2"></i> Notifications</a></li>
          <li class="nav-item"><a class="nav-link " href="rup.php"><i class="bi bi-star me-2"></i> Reviews</a></li>
        </ul>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
      <h2 class="fw-bold mb-4">My Reviews & Ratings</h2>

      <div class="card shadow-sm mb-4">
        <div class="card-body">
          <h5 class="card-title">Submit a Review</h5>
          <form>
            <div class="mb-3">
              <label for="bookingId" class="form-label">Booking ID</label>
              <input type="text" class="form-control" id="bookingId" placeholder="Enter Booking ID">
            </div>
            <div class="mb-3">
              <label for="rating" class="form-label">Rating</label>
              <select class="form-select" id="rating">
                <option selected disabled>Select Rating</option>
                <option>⭐️⭐️⭐️⭐️⭐️</option>
                <option>⭐️⭐️⭐️⭐️</option>
                <option>⭐️⭐️⭐️</option>
                <option>⭐️⭐️</option>
                <option>⭐️</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="review" class="form-label">Review</label>
              <textarea class="form-control" id="review" rows="3" placeholder="Write your review here..."></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit Review</button>
          </form>
        </div>
      </div>

      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="card-title">My Previous Reviews</h5>
          <ul class="list-group">
            <li class="list-group-item">
              <strong>Booking ID: BK123</strong><br/>
              ⭐️⭐️⭐️⭐️⭐️<br/>
              Excellent facility and easy booking process!
            </li>
            <li class="list-group-item">
              <strong>Booking ID: BK120</strong><br/>
              ⭐️⭐️⭐️⭐️<br/>
              Good trainer, but the equipment could be improved.
            </li>
          </ul>
        </div>
      </div>

    </main>
  </div>
</div>

<script src="js/common.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
