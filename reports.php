<?php
include 'db_connect.php';
session_start();

// Only admins can access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch counts
$total_users = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];
$total_bookings = $conn->query("SELECT COUNT(*) AS total FROM bookings")->fetch_assoc()['total'] ?? 0;
$total_centres = $conn->query("SELECT COUNT(*) AS total FROM centres")->fetch_assoc()['total'];

// Safe revenue fallback (since amount_paid might not exist yet)
$total_revenue = 0.00;

// Optional: Check if 'amount_paid' column exists before using it
$check = $conn->query("SHOW COLUMNS FROM bookings LIKE 'amount_paid'");
if ($check && $check->num_rows > 0) {
    $rev = $conn->query("SELECT SUM(amount_paid) AS total FROM bookings WHERE status = 'completed'");
    $total_revenue = $rev->fetch_assoc()['total'] ?? 0.00;
}

// Optional: Check if 'booking_date' exists before using it in query
$hasBookingDate = $conn->query("SHOW COLUMNS FROM bookings LIKE 'booking_date'")->num_rows > 0;
$bookingOrderBy = $hasBookingDate ? "ORDER BY b.booking_date DESC" : "";

// Recent bookings (safe query)
$recent_bookings = $conn->query("
    SELECT b.booking_id, u.name, c.name AS centre_name, b.status
    FROM bookings b
    JOIN users u ON b.user_id = u.user_id
    JOIN centres c ON b.centre_id = c.centre_id
    $bookingOrderBy
    LIMIT 10
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reports - Indoor Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h2 class="mb-4 text-center">System Reports</h2>

    <!-- System Overview Cards -->
    <div class="row g-4 mb-4 text-center">
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Total Users</h5>
                    <p class="display-6"><?= $total_users ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Total Bookings</h5>
                    <p class="display-6"><?= $total_bookings ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Indoor Centres</h5>
                    <p class="display-6"><?= $total_centres ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Total Revenue</h5>
                    <p class="display-6">Rs. <?= number_format($total_revenue, 2) ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Booking Table -->
    <div class="card">
        <div class="card-header bg-primary text-white">Recent Bookings</div>
        <div class="card-body p-0">
            <table class="table table-bordered table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Centre</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($recent_bookings && $recent_bookings->num_rows > 0): ?>
                        <?php while ($row = $recent_bookings->fetch_assoc()): ?>
                            <tr>
                                <td><?= $row['booking_id'] ?></td>
                                <td><?= htmlspecialchars($row['name']) ?></td>
                                <td><?= htmlspecialchars($row['centre_name']) ?></td>
                                <td><?= ucfirst($row['status']) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="4" class="text-center">No bookings found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
</body>
</html>
