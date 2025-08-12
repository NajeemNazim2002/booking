<?php
session_start();
include 'db_con/db_connect.php';

// Check if user is logged in and is a manager
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'manager') {
    header("Location: login.php");
    exit();
}

// Get manager's centre
$manager_id = $_SESSION['user_id'];
$centre_query = "SELECT c.* FROM centres c 
                 INNER JOIN centre_managers cm ON c.centre_id = cm.centre_id 
                 WHERE cm.user_id = ?";
$stmt = $conn->prepare($centre_query);
$stmt->bind_param("i", $manager_id);
$stmt->execute();
$centre = $stmt->get_result()->fetch_assoc();

// Get statistics for different time periods
function getStats($conn, $centre_id, $days) {
    $query = "SELECT 
              COUNT(*) as total_bookings,
              SUM(CASE WHEN status = 'confirmed' THEN 1 ELSE 0 END) as confirmed_bookings,
              SUM(CASE WHEN payment_status = 'paid' THEN amount ELSE 0 END) as total_revenue
              FROM bookings 
              WHERE centre_id = ? 
              AND booking_date >= DATE_SUB(CURRENT_DATE, INTERVAL ? DAY)
              AND booking_date <= CURRENT_DATE";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $centre_id, $days);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Get statistics for different periods
$stats_7days = getStats($conn, $centre['centre_id'], 7);
$stats_30days = getStats($conn, $centre['centre_id'], 30);
$stats_90days = getStats($conn, $centre['centre_id'], 90);

// Get popular time slots
$popular_slots_query = "SELECT 
                        DATE_FORMAT(start_time, '%H:%i') as slot_time,
                        COUNT(*) as booking_count
                        FROM bookings 
                        WHERE centre_id = ? 
                        AND booking_date >= DATE_SUB(CURRENT_DATE, INTERVAL 30 DAY)
                        GROUP BY slot_time
                        ORDER BY booking_count DESC
                        LIMIT 5";
$stmt = $conn->prepare($popular_slots_query);
$stmt->bind_param("i", $centre['centre_id']);
$stmt->execute();
$popular_slots = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reports - Indoor Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-light">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="manager_dashboard.php">
                <img src="images/logo.jpg" alt="Indoor Hub Logo" width="40" height="40" class="me-2">
                Centre Manager Dashboard
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="manager_dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manager_bookings.php">Bookings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manager_staff.php">Staff</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="manager_reports.php">Reports</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <div class="row mb-4">
            <div class="col">
                <h2>Reports - <?= htmlspecialchars($centre['name']) ?></h2>
                <p class="text-muted">View statistics and reports for your centre</p>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <!-- Last 7 Days -->
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Last 7 Days</h5>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>Total Bookings:</span>
                            <strong><?= $stats_7days['total_bookings'] ?></strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>Confirmed Bookings:</span>
                            <strong><?= $stats_7days['confirmed_bookings'] ?></strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Revenue:</span>
                            <strong>$<?= number_format($stats_7days['total_revenue'], 2) ?></strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Last 30 Days -->
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Last 30 Days</h5>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>Total Bookings:</span>
                            <strong><?= $stats_30days['total_bookings'] ?></strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>Confirmed Bookings:</span>
                            <strong><?= $stats_30days['confirmed_bookings'] ?></strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Revenue:</span>
                            <strong>$<?= number_format($stats_30days['total_revenue'], 2) ?></strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Last 90 Days -->
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Last 90 Days</h5>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>Total Bookings:</span>
                            <strong><?= $stats_90days['total_bookings'] ?></strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>Confirmed Bookings:</span>
                            <strong><?= $stats_90days['confirmed_bookings'] ?></strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Revenue:</span>
                            <strong>$<?= number_format($stats_90days['total_revenue'], 2) ?></strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Popular Time Slots -->
        <div class="row">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Popular Time Slots</h5>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Time Slot</th>
                                        <th>Number of Bookings</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($slot = $popular_slots->fetch_assoc()): ?>
                                        <tr>
                                            <td><?= date('h:i A', strtotime($slot['slot_time'])) ?></td>
                                            <td><?= $slot['booking_count'] ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
