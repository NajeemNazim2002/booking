<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'manager') {
    header("Location: login.php");
    exit();
}

include 'db_connect.php';
$manager_id = $_SESSION['user_id'];

// Get the centre managed by this manager
$centre = $conn->query("SELECT * FROM centres WHERE manager_id = $manager_id")->fetch_assoc();
$centre_id = $centre['centre_id'] ?? 0;

// Fetch bookings for this centre
$bookings = [];
if ($centre_id) {
    $sql = "SELECT b.*, u.name AS user_name, u.email 
            FROM bookings b 
            JOIN users u ON b.user_id = u.user_id 
            WHERE b.centre_id = ? 
            ORDER BY b.booking_date DESC, b.start_time DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $centre_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $bookings[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Bookings | Centre Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container py-5">
    <h2 class="mb-4 text-center">Bookings for <?= htmlspecialchars($centre['name'] ?? 'Your Centre') ?></h2>
    <?php if (!$centre_id): ?>
        <div class="alert alert-warning text-center">No centre assigned to your account.</div>
    <?php else: ?>
        <?php if (empty($bookings)): ?>
            <div class="alert alert-info text-center">No bookings found for this centre.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Email</th>
                            <th>Booking Date</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bookings as $i => $b): ?>
                        <tr>
                            <td><?= $i+1 ?></td>
                            <td><?= htmlspecialchars($b['user_name']) ?></td>
                            <td><?= htmlspecialchars($b['email']) ?></td>
                            <td><?= htmlspecialchars($b['booking_date']) ?></td>
                            <td><?= htmlspecialchars($b['start_time']) ?></td>
                            <td><?= htmlspecialchars($b['end_time']) ?></td>
                            <td><?= htmlspecialchars(ucfirst($b['status'])) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    <?php endif; ?>
    <div class="mt-4 text-center">
        <a href="manager_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>
</div>
</body>
</html>