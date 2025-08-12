<?php
session_start();
include 'db_con/db_connect.php';

// Ensure user is a manager
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'manager') {
    header("Location: login.php");
    exit();
}

// Get manager's centre
$user_id = $_SESSION['user_id'];
$centre_query = "SELECT c.* FROM centres c 
                 INNER JOIN centre_managers cm ON c.centre_id = cm.centre_id 
                 WHERE cm.user_id = ?";
$stmt = $conn->prepare($centre_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$centre = $stmt->get_result()->fetch_assoc();

// Get all bookings for the centre
$bookings_query = "SELECT b.*, u.name as user_name, 
                   DATE_FORMAT(b.booking_date, '%d-%m-%Y') as formatted_date,
                   TIME_FORMAT(b.start_time, '%h:%i %p') as formatted_start_time,
                   TIME_FORMAT(b.end_time, '%h:%i %p') as formatted_end_time
                   FROM bookings b 
                   INNER JOIN users u ON b.user_id = u.user_id
                   WHERE b.centre_id = ?
                   ORDER BY b.booking_date DESC, b.start_time DESC";
$stmt = $conn->prepare($bookings_query);
$stmt->bind_param("i", $centre['centre_id']);
$stmt->execute();
$bookings_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Bookings - Indoor Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="manager_dashboard.php">Manager Dashboard</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="manager_dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link active" href="view_bookings.php">View Bookings</a></li>
                    <li class="nav-item"><a class="nav-link" href="manage_staff.php">Manage Staff</a></li>
                    <li class="nav-item"><a class="nav-link" href="reports.php">Reports</a></li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <div class="row mb-4">
            <div class="col">
                <h2>Bookings for <?= htmlspecialchars($centre['name']) ?></h2>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="bookingsTable">
                        <thead>
                            <tr>
                                <th>Booking Date</th>
                                <th>Time</th>
                                <th>User</th>
                                <th>Status</th>
                                <th>Payment</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($booking = $bookings_result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $booking['formatted_date'] ?></td>
                                    <td><?= $booking['formatted_start_time'] ?> - <?= $booking['formatted_end_time'] ?></td>
                                    <td><?= htmlspecialchars($booking['user_name']) ?></td>
                                    <td>
                                        <span class="badge bg-<?= 
                                            $booking['status'] === 'confirmed' ? 'success' : 
                                            ($booking['status'] === 'pending' ? 'warning' : 
                                             ($booking['status'] === 'cancelled' ? 'danger' : 'secondary')) 
                                        ?>">
                                            <?= ucfirst($booking['status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= $booking['payment_status'] === 'paid' ? 'success' : 'warning' ?>">
                                            <?= ucfirst($booking['payment_status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($booking['status'] === 'pending'): ?>
                                            <button class="btn btn-success btn-sm confirm-booking" 
                                                    data-booking-id="<?= $booking['booking_id'] ?>">
                                                Confirm
                                            </button>
                                            <button class="btn btn-danger btn-sm reject-booking"
                                                    data-booking-id="<?= $booking['booking_id'] ?>">
                                                Reject
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#bookingsTable').DataTable({
                order: [[0, 'desc'], [1, 'desc']]
            });
        });
    </script>
</body>
</html>
